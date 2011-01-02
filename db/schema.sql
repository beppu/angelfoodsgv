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
