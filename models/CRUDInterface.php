<?php
namespace dbPdo;

interface crud
{
    public function create($data);
    public function readAll();
    public function readById($id);
    public function updateById($data);
    public function deleteById($id);
}