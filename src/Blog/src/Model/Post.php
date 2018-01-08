<?php
/**
 * Julien Guittard Website
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/julienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://julienguittard.com)
 */

namespace Blog\Model;

use DateTime;
use Zend\Stdlib\ArraySerializableInterface;

/**
 * Class Post
 *
 * @package Blog\Model
 */
class Post implements ArraySerializableInterface
{
    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var DateTime
     */
    protected $created;

    /**
     * @var DateTime
     */
    protected $updated;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $author;

    /**
     * @var bool
     */
    protected $draft;

    /**
     * @var bool
     */
    protected $public;

    /**
     * @var string
     */
    protected $excerpt;

    /**
     * @var string
     */
    protected $tags;

    /**
     * Exchange internal values from provided array
     *
     * @param  array $array
     * @return void
     */
    public function exchangeArray(array $array)
    {
        $this->slug = $array['slug'];
        $this->path = $array['path'];
        $this->created = (new DateTime($array['created']))->getTimestamp();
        $this->updated = (new DateTime($array['updated']))->getTimestamp();
        $this->title = $array['title'];
        $this->author = $array['author'];
        $this->draft = (bool)$array['draft'];
        $this->public = (bool)$array['public'];
        $this->excerpt = $array['excerpt'];
        $this->tags = sprintf('|%s|', implode('|', $array['tags']));
    }

    /**
     * Return an array representation of the object
     *
     * @return array
     */
    public function getArrayCopy(): array
    {
        return get_object_vars($this);
    }
}
