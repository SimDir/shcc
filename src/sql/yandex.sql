CREATE TABLE yandex_devices (
    id bigint PRIMARY KEY GENERATED BY DEFAULT AS IDENTITY,
    user_id bigint REFERENCES auth_users(id),
    uid varchar(64) NOT NULL,
    name varchar(64),
    description text,
    place_id bigint REFERENCES places(id),
    type varchar(64) NOT NULL,
    capabilities jsonb NOT NULL,
    device_id bigint REFERENCES devices(id)
);
CREATE UNIQUE INDEX yandex_devices_deivce_id_idx ON yandex_devices (device_id);
COMMENT ON TABLE yandex_devices IS 'Виртуальные устройсва для умного дома Яндекс';