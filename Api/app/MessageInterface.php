<?php

namespace App;

interface MessageInterface
{
    public function showUser();
    public function show(string $id);
    public function store(array $data);
}
