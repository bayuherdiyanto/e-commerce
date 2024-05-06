CREATE TABLE `com-detail_keranjang` ( `id` INT(20) NOT NULL AUTO_INCREMENT , `id_keranjang` VARCHAR(100) NULL , `item_id` VARCHAR(100) NULL , `item_name` VARCHAR(200) NULL , `qty` VARCHAR(100) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB; 
ALTER TABLE `com-detail_keranjang` ADD `harga` VARCHAR(100) NULL AFTER `qty`, ADD `total` VARCHAR(100) NULL AFTER `harga`; 
ALTER TABLE `com-detail_keranjang` ADD `diskon` VARCHAR(100) NULL AFTER `harga`; 
ALTER TABLE `com-detail_keranjang` ADD `id_satuan` VARCHAR(100) NULL AFTER `harga`; 