CREATE TABLE ot_translations (
  translation_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  url TEXT NOT NULL,
  native_locale_code VARCHAR(10) NOT NULL,
  native_text LONGTEXT NOT NULL,
  translated_locale_code VARCHAR(10) NOT NULL,
  translated_text LONGTEXT NOT NULL,
  ip INT NOT NULL,
  vote_down INT default 0,
  vote_up INT default 0,
  status tinyint default 0,
  PRIMARY KEY (translation_id)
) ENGINE=INNODB DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
