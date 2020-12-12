<?php

use Doctrine\DBAL\Schema\Schema;

$schema = new Schema();

$tables = [];

$t = $schema->createTable('tbl_products');
$t->addColumn("id", "integer", ["unsigned" => true])->setAutoincrement(true);
$t->setPrimaryKey(["id"]);
// created_on and update_on are going to be used by the Timestamps behaviour
$t->addColumn("created_on", "datetime")->setNotnull(false);
$t->addColumn("updated_on", "datetime")->setNotnull(false);
// the products mapper uses soft deletes
$t->addColumn("deleted_on", "datetime")->setNotnull(false);
// many products to one category links
$t->addColumn("category_id", "integer", ["unsigned" => true])->setNotnull(false);
$t->addColumn("sku", "string", ["length" => 255]);
$t->addColumn("title", "string", ["length" => 255]);
$t->addColumn("description", "string", ["length" => 255]);
$t->addColumn("price", "decimal", ["precision" => 14, 'scale' => 2])->setDefault(0);
// for demonstrating casting a column into an ArrayObject
$t->addColumn("attributes", "json")->setNotnull(false);

$tables[$t->getName()] = $t;

// for testing one-to-one relations
// stores the details of the product being publishd on Ebay
$t = $schema->createTable('tbl_ebay_products');
$t->addColumn("id", "integer", ["unsigned" => true])->setAutoincrement(true);
$t->addColumn("product_id", "integer", ["unsigned" => true])->setNotnull(false);
$t->addColumn("price", "decimal", ["precision" => 14, 'scale' => 2])->setDefault(0);
$t->addColumn("is_active", "boolean")->setDefault(true);
$t->setPrimaryKey(["id"]);

$tables[$t->getName()] = $t;

// images table holds images for multiple types of content
// this is for testing one-to-many and many-to-one via using a mapper that has guards
$t = $schema->createTable('tbl_images');
$t->addColumn("id", "integer", ["unsigned" => true])->setAutoincrement(true);
$t->setPrimaryKey(["id"]);
$t->addColumn("content_id", "integer", ["unsigned" => true]);
$t->addColumn("content_type", "string", ["length" => 64]);
$t->addColumn("name", "string", ["length" => 255]);
$t->addColumn("folder", "string", ["length" => 255])->setNotnull(false);

$tables[$t->getName()] = $t;

// categories table
// for testing parent-child relations (ie: one-to-many and many-to-one using non-standard column names)
$t = $schema->createTable('tbl_categories');
$t->addColumn("id", "integer", ["unsigned" => true])->setAutoincrement(true);
$t->addColumn("parent_id", "integer", ["unsigned" => true])->setNotnull(false);
$t->addColumn("name", "string", ["length" => 255]);
$t->addColumn("position", "integer", ['unsigned' => true])->setDefault(0);
$t->setPrimaryKey(["id"]);

$tables[$t->getName()] = $t;

// tags
// for testing many-to-many relations
$t = $schema->createTable('tbl_tags');
$t->addColumn("id", "integer", ["unsigned" => true])->setAutoincrement(true);
$t->addColumn("name", "string", ["length" => 255]);
$t->setPrimaryKey(["id"]);

$tables[$t->getName()] = $t;

// the pivot table store relations between tags and multiple content types
// we're going to use pivot guards when defining the relations
$t = $schema->createTable('tbl_links_to_tags');
$t->addColumn("tagable_type", "string", ["length" => 255]);
$t->addColumn("tagable_id", "integer", ["unsigned" => true]);
$t->addColumn("tag_id", "integer", ["unsigned" => true]);
$t->addColumn("position", "integer", ["unsigned" => true])->setDefault(0);
$t->addUniqueIndex(["tagable_type", "tagable_id", "tag_id"]);

$tables[$t->getName()] = $t;

return $schema;
