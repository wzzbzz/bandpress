<?php

namespace vinepress\Views\ComponentViews;

// renders a linked list of entities.
// must have implemented name() and url()
class LinkListView
{
    protected $list;
    public function __construct($list)
    {
        $this->list = $list;
    }

    public function render()
    {
        if (empty($this->list)) {
            $this->renderEmpty();
        }
        else{
            $this->renderList();
        }
    }

    protected function renderEmpty()
    {
        ?>
            <ul class='list-unstyled'>
                <li>You have none.</li>
            </ul>
        <?php 
    }

    protected function renderList()
    {
        ?>
            <ul class="list-unstyled">
            <?php foreach($this->list as $item):?>
                <li><a href="<?php echo $item->url();?>"><?php echo $item->name();?></a></li>
            <?php endforeach;?>
            </ul>
        <?php
    }
}