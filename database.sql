CREATE TABLE users (
  id INTEGER UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  cedula VARCHAR(10) NOT NULL,
  cargo VARCHAR(25) NOT NULL,
  direccion VARCHAR(25) NOT NULL,
  telefono VARCHAR(10) NOT NULL,
  contrasena VARCHAR(255) NOT NULL,
  correo VARCHAR(50) NOT NULL,
  estado BOOLEAN NOT NULL,
  nombre VARCHAR(25) NOT NULL,
  apellido VARCHAR(25) NOT NULL,
  created_at datetime DEFAULT NULL,
  updated_at datetime DEFAULT NULL,
  PRIMARY KEY(id),
  UNIQUE (cedula)
)
ENGINE=InnoDB;

CREATE TABLE proveedores (
  id INTEGER UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  nit VARCHAR(10) NOT NULL,
  nombre VARCHAR(25) NOT NULL,
  telefono VARCHAR(10) NOT NULL,
  direccion VARCHAR(25) NOT NULL,
  created_at datetime DEFAULT NULL,
  updated_at datetime DEFAULT NULL,
  PRIMARY KEY(id),
  UNIQUE (nit)
)
ENGINE=InnoDB;

CREATE TABLE clientes (
  id INTEGER UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  cedula VARCHAR(10) NOT NULL,
  nombre VARCHAR(25) NOT NULL,
  apellido VARCHAR(25) NOT NULL,
  telefono VARCHAR(25) NOT NULL,
  created_at datetime DEFAULT NULL,
  updated_at datetime DEFAULT NULL,
  PRIMARY KEY(id),
  UNIQUE (cedula)
)
ENGINE=InnoDB;

CREATE TABLE productos (
  id INTEGER UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  codigo VARCHAR(10) NOT NULL,
  proveedores_id INTEGER UNSIGNED ZEROFILL NOT NULL ,
  nombre VARCHAR(25) NOT NULL,
  precioUnidad FLOAT NOT NULL,
  cantidad INT NOT NULL,
  presentacion VARCHAR(25) NOT NULL,
  costoCompra FLOAT NOT NULL,
  precioDocena FLOAT NOT NULL,
  created_at datetime DEFAULT NULL,
  updated_at datetime DEFAULT NULL,
  PRIMARY KEY(id),
  UNIQUE (codigo),
  INDEX Productos_FKIndex2(proveedores_id),
  FOREIGN KEY(proveedores_id)
    REFERENCES proveedores(id)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
)
ENGINE=InnoDB;

CREATE TABLE facturas (
  id INTEGER UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
  clientes_id INTEGER UNSIGNED ZEROFILL NOT NULL,
  user_id INTEGER UNSIGNED ZEROFILL NOT NULL,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  total FLOAT NOT NULL,
  estados VARCHAR(25) NOT NULL,
  created_at datetime DEFAULT NULL,
  updated_at datetime DEFAULT NULL,
  PRIMARY KEY(id),
  INDEX Facturas_FKIndex2(user_id),
  INDEX Facturas_FKIndex1(clientes_id),
  FOREIGN KEY(user_id)
    REFERENCES users(id)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(clientes_id)
    REFERENCES clientes(id)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
)
ENGINE=InnoDB;

CREATE TABLE detalleFactura (
  productos_id INTEGER UNSIGNED ZEROFILL NOT NULL,
  facturas_id INTEGER UNSIGNED ZEROFILL NOT NULL,
  cantidadCompra INT NOT NULL,
  created_at datetime DEFAULT NULL,
  updated_at datetime DEFAULT NULL,
  PRIMARY KEY(productos_id, facturas_id),
  INDEX Productos_has_Facturas_FKIndex2(facturas_id),
  FOREIGN KEY(productos_id)
    REFERENCES productos(id)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION,
  FOREIGN KEY(facturas_id)
    REFERENCES facturas(id)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
)
ENGINE=InnoDB;
