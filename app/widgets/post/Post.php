<?php

namespace app\widgets\post;

use app\base\Widget;
use app\widgets\WidgetInterface;
use app\widgets\WidgetNameGetterTrait;

class Post extends Widget implements WidgetInterface
{
    use WidgetNameGetterTrait;

    /** @var array  */
    private $postData;

    /** @var string */
    private $brokenFilePath;

    public function __construct(array $postData)
    {
        parent::__construct();
        $this->postData = $postData;
        $this->brokenFilePath = DS . 'widgets' . DS . 'post' . DS . 'broken-file.png';
    }

    public function render(): void
    {
        include(__DIR__ . DS . 'post-layout.php');
    }
}
