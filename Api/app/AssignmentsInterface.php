<?php

namespace App;

interface AssignmentsInterface
{
    public function index();
    public function show(string $id);
    public function showUser();
    public function store(array $data);
    public function update(array $data, string $id);
    public function destroy(string $id);
}
