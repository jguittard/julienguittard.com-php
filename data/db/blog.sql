CREATE TABLE "posts" (
  slug VARCHAR(255) NOT NULL PRIMARY KEY,
  path VARCHAR(255) NOT NULL,
  created UNSIGNED INTEGER NOT NULL,
  updated UNSIGNED INTEGER NOT NULL,
  title VARCHAR(255) NOT NULL,
  author VARCHAR(255) NOT NULL,
  draft INT(1) NOT NULL,
  public INT(1) NOT NULL,
  excerpt TEXT NOT NULL,
  tags VARCHAR(255)
);