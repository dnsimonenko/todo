<?php

namespace CQRSBlog\BlogEngine\DomainModel;

interface TodoViewRepository
{
    public function get($id);

    public function all();
}
