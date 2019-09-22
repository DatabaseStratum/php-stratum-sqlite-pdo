-- ---------------------------------------------------------------------------------------------------------------------
drop table if exists TST_FOO1;

create table TST_FOO1( tst_int  int
,                      tst_real real
,                      tst_text text
,                      tst_blob blob )
;

-- ---------------------------------------------------------------------------------------------------------------------
drop table if exists TST_FOO2;

create table TST_FOO2( tst_c00 int
,                      tst_c01 varchar
,                      tst_c02 varchar
,                      tst_c03 varchar
,                      tst_c04 varchar )
;

insert into TST_FOO2( tst_c00
,                     tst_c01
,                     tst_c02
,                     tst_c03
,                     tst_c04 )
values( 1
,       'a'
,       'b'
,       'c1'
,       'd' )
,      ( 2
,       'a'
,       'b'
,       'c2'
,       'd' )
,      ( 3
,       'a'
,       'b'
,       'c3'
,       'd' )
;

-- ---------------------------------------------------------------------------------------------------------------------
drop table if exists TST_TABLE;

create table TST_TABLE( tst_c00 varchar
,                       tst_c01 int
,                       tst_c02 real
,                       tst_c03 varchar
,                       tst_c04 varchar
,                       t       int
,                       s       int )
;

insert into TST_TABLE( tst_c00
,                      tst_c01
,                      tst_c02
,                      tst_c03
,                      tst_c04
,                      t
,                      s )
values( 'Hello'
,       1
,       '0.543'
,       '1.2345'
,       '2014-03-27 00:00:00'
,       '4444'
,       '1' )
,      ( 'World'
,        3
,        '3E-05'
,        0
,        '2014-03-28 00:00:00'
,        null
,        1 )
;

-- ---------------------------------------------------------------------------------------------------------------------
drop table if exists TST_LABEL;

create table TST_LABEL( tst_id    int auto_increment
,                       tst_test  varchar
,                       tst_label varchar
,  primary key(tst_id))
;

insert into TST_LABEL( tst_test
,                      tst_label )
values( 'spam'
,       'TST_ID_SPAM')
,     ( 'eggs'
,       'TST_ID_EGGS')
,     ( 'bunny'
,       'TST_ID_BUNNY')
,     ( 'cat'
,       'TST_ID_CAT')
,     ( 'elephant'
,       'TST_ID_ELEPHANT')
;

-- ---------------------------------------------------------------------------------------------------------------------
