#!/bin/bash

if [ -z ${DH_SIZE+x} ]
then
  >&2 echo ">> no \$DH_SIZE specified using default"
  DH_SIZE="512"
fi


DH="/etc/nginx/certs/dhparams.pem"

if [ ! -e "$DH" ]
then
  echo ">> seems like the first start of nginx"
  echo ">> doing some preparations..."
  echo ""

  echo ">> generating $DH with size: $DH_SIZE"
  openssl dhparam -out "$DH" $DH_SIZE
fi

FQDN="julienguittard.local"
PP="/etc/ssl/ca/passphrase.txt"

REQ="$FQDN.csr.pem"
CERT="$FQDN.cert.pem"
KEY="$FQDN.key.pem"

if [ ! -f /etc/nginx/certs/"$FQDN"-bundle.cert.pem ]
then
    # Create a key
    echo "** SIGN SERVER AND CLIENT CERTIFICATES"
    echo ">> Prepping the folders"
    cd /etc/ssl/ca
    mkdir crl csr newcerts private
    chmod 700 private
    touch index.txt.attr
    touch index.txt
    echo 1000 > serial
    echo 1000 > crlnumber

    echo ">> Creating a key"
    openssl genrsa \
          -out /etc/ssl/private/"$KEY" 2048
    chmod 400 /etc/ssl/private/"$KEY"

    # Create a certificate
    echo ">> Creating a certificate"
    openssl req -config /etc/ssl/ca/openssl.cnf \
        -passin file:"$PP" \
        -key /etc/ssl/private/"$KEY" \
        -new -sha256 -out /etc/ssl/private/"$REQ"

    openssl ca -config /etc/ssl/ca/openssl.cnf \
        -passin file:"$PP" \
        -batch \
        -extensions server_cert -days 375 -notext -md sha256 \
        -in /etc/ssl/private/"$REQ" \
        -out /etc/nginx/certs/"$CERT"
    chmod 444 /etc/nginx/certs/"$CERT"

    cat /etc/nginx/certs/"$CERT" /etc/ssl/ca/certs/julienguittard-ca-chain.cert.pem > /etc/nginx/certs/"$FQDN"-bundle.cert.pem
fi

# exec CMD
echo ">> exec docker CMD"
echo "$@"
exec "$@"