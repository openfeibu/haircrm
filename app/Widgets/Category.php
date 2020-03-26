<?php
namespace App\Widgets;

use Tree;
use Teepluss\Theme\Theme;
use Teepluss\Theme\Widget;
use App\Models\Category as CategoryModel;

class Category extends Widget {

    /**
     * Widget template.
     *
     * @var string
     */
    public $template = 'category';

    /**
     * Watching widget tpl on everywhere.
     *
     * @var boolean
     */
    public $watch = false;

    /**
     * Arrtibutes pass from a widget.
     *
     * @var array
     */
    public $attributes = array();

    /**
     * Turn on/off widget.
     *
     * @var boolean
     */
    public $enable = true;

    /**
     * Code to start this widget.
     *
     * @return void
     */
    public function init(Theme $theme)
    {

    }

    /**
     * Logic given to a widget and pass to widget's view.
     *
     * @return array
     */
    public function run()
    {

        $top_categories = CategoryModel::where('parent_id',0)->get();

        $this->setAttribute('top_categories',$top_categories);

        $attributes = $this->getAttributes();

        return $attributes;
    }

}