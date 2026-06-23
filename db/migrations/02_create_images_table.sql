
CREATE TABLE IF NOT EXISTS images (
    id  INTEGER PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    imageable_id  INTEGER NOT NULL,
    imageable_type  VARCHAR NOT NULL,
    variant  VARCHAR NOT NULL DEFAULT 'image',
    src  VARCHAR,
    alt  VARCHAR NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_images_imageable 
ON images (imageable_id, imageable_type);
