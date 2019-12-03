<?php

use Phinx\Migration\AbstractMigration;

class Badges extends AbstractMigration
{
    public function change()
    {
        $sql = <<<EOF
CREATE TABLE `afup_badge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
)
EOF;
        $this->execute($sql);
    }
}
