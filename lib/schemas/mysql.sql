CREATE TABLE ot_translations (
  translation_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  locale_code VARCHAR(10) NOT NULL,
  original LONGTEXT NOT NULL,
  translation LONGTEXT NOT NULL,
  score SMALLINT SIGNED DEFAULT 0
  PRIMARY KEY (translation_id)
) ENGINE=INNODB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;