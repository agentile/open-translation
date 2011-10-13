CREATE TABLE ot_translations (
  translation_id serial,
  url text not null,
  native_locale_code text not null,
  native_text text not null,
  translated_locale_code text not null,
  translated_text text not null,
  ip integer not null,
  vote_down integer default 0,
  vote_up integer default 0
);
