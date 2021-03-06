-- Menu has_many MenuItems
create table menu (
  id int not null primary key auto_increment,
  year int not null,
  month int not null,
  regular_price decimal(8,2),
  double_price decimal(8,2),
  created_on datetime,
  modified_on timestamp,
  unique key (year, month)
);

-- MenuItem belongs_to Menu
create table menu_item (
  id int not null primary key auto_increment,
  menu_id int not null,
  day int not null,
  t enum('food', 'dismissal', 'holiday'),
  title varchar(32),
  body text,
  created_on datetime,
  modified_on timestamp,
  unique key (menu_id, day)
);

-- This table is special, and it should only ever have one row in it.
create table config (
  id int,
  current_menu_id int
);

insert into config values (1, 1);

-- Purchase has_many PurchaseItems
create table purchase (
  id int not null primary key auto_increment,
  menu_id int not null,
  session_id varchar(48) not null,
  receipt_id varchar(40),
  family_name varchar(48) not null,
  phone_number varchar(20) not null,
  status enum('pending', 'cancelled', 'paid') not null default 'pending',
  created_on datetime,
  modified_on timestamp
  -- XXX - Might want to save PayPal token.
  -- XXX - Might want to save $_ENV['REMOTE_ADDR']
);

-- PurchaseItem belongs_to Purchase
create table purchase_item (
  id int not null primary key auto_increment,
  purchase_id int not null,
  day int not null,
  t enum('regular','double') not null default 'regular',
  child_name varchar(48) not null,
  child_grade int not null,
  price decimal(8,2) not null,
  created_on datetime,
  modified_on timestamp
);
