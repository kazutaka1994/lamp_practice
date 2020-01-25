-- orders --
    CREATE TABLE sample.orders (
        order_id INT NOT NULL AUTO_INCREMENT ,
        user_id INT NOT NULL ,
        created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
        updated DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
        PRIMARY KEY (order_id)
    ) ENGINE = InnoDB;
-- details --
    CREATE TABLE sample.details (
        detail_id INT NOT NULL AUTO_INCREMENT ,
        order_id INT NOT NULL ,
        item_id INT NOT NULL ,
        price INT NOT NULL ,
        amount INT NOT NULL ,
        created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
        updated DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
        PRIMARY KEY (detail_id)
    ) ENGINE = InnoDB;