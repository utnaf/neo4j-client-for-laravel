MATCH (n) DETACH DELETE n;

DROP CONSTRAINT user_id_unique;
DROP CONSTRAINT user_email_unique;
