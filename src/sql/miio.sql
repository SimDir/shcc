CREATE TABLE miio_tokens (
    id bigint PRIMARY KEY GENERATED BY DEFAULT AS IDENTITY,
    uid varchar(64) NOT NULL,
    token varchar(32)
);