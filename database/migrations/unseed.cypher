MATCH (n) DETACH DELETE n;

DROP CONSTRAINT user_id_unique;
DROP CONSTRAINT user_email_unique;
DROP CONSTRAINT brewery_id_unique;
DROP CONSTRAINT beer_id_unique;
DROP CONSTRAINT style_unique;
