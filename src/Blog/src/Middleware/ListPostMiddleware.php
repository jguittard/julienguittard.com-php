<?php
/**
 * Julien Guittard Website
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/julienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://julienguittard.com)
 */

namespace Blog\Middleware;

use Blog\MarkdownFilter;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use League\Flysystem\Filesystem;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class ListPostMiddleware
 *
 * @package Blog\Middleware
 */
class ListPostMiddleware implements MiddlewareInterface
{
    /**
     * @var TemplateRendererInterface
     */
    private $template;

    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * ListPostMiddleware constructor.
     *
     * @param TemplateRendererInterface $template
     * @param Filesystem $fileSystem
     */
    public function __construct(TemplateRendererInterface $template, Filesystem $fileSystem)
    {
        $this->template = $template;
        $this->fileSystem = $fileSystem;
        $this->fileSystem->addPlugin(new MarkdownFilter());
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $paths = array_reverse($this->fileSystem->listMarkdown());
        return new HtmlResponse(
            $this->template->render('blog::post-list')
        );
    }
}
