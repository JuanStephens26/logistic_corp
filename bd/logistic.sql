-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 13-12-2022 a las 00:10:27
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `logistic`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_asignar_entrada_lin` (IN `pno_receipt` VARCHAR(50), IN `pno_line` VARCHAR(50), IN `pno_locc` VARCHAR(50), IN `puser_work` VARCHAR(50), IN `pqty` VARCHAR(50), IN `puser_session` VARCHAR(50), IN `pindicador` VARCHAR(50))   BEGIN

declare vqty int;
declare vlocated varchar(1) default 'N';
declare vstatus varchar(1);
declare vexists  int;

if pindicador = 'AI' THEN

update tbl_receipt_dtl 
set user_work = puser_work,
 	no_locc = pno_locc,
    modify_date = sysdate(),
    user_modify_date = puser_session
    where no_receipt = pno_receipt
    AND no_line = pno_line;

END IF;

if pindicador = 'LO' THEN

select qty into vqty from tbl_receipt_dtl where no_receipt = pno_receipt
    AND no_line = pno_line;

if vqty = pqty THEN
 set vlocated = 'S';
end if;

update tbl_receipt_dtl 
set qty_located = pqty,
located_flag = vlocated,
    modify_date = sysdate(),
    user_modify_date = puser_session
    where no_receipt = pno_receipt
    AND no_line = pno_line;

select case when sum(qty) > sum(qty_located) then 'A' else 'F' end
into vstatus 
from tbl_receipt_dtl
where no_receipt = pno_receipt;

update tbl_receipt
set status= vstatus,
    modify_date = sysdate(),
    user_modify_date = puser_session
    where no_receipt = pno_receipt;

select count(*)
into vexists 
from tbl_location_dtl d 
left JOIN tbl_receipt_dtl r ON d.no_locc = r.no_locc and d.prtnum = r.prtnum 
AND d.client_id = r.client_id
where r.no_receipt = pno_receipt AND r.no_line = pno_line;

IF vexists = 0 THEN

insert into tbl_location_dtl (no_locc, prtnum, lotnum, qty, client_id,
                             add_date, user_add_date)
              select d.no_locc, d.prtnum, p.lotnum, d.qty_located, d.client_id,
              sysdate(), puser_session
              from tbl_receipt_dtl d 
              LEFT JOIN tbl_product p ON p.prtnum = d.prtnum
              where no_receipt = pno_receipt
    AND no_line = pno_line;

else 

update tbl_location_dtl d 
left JOIN tbl_receipt_dtl r ON d.no_locc = r.no_locc and d.prtnum = r.prtnum 
AND d.client_id = r.client_id
set d.qty = d.qty + pqty
where r.no_receipt = pno_receipt AND r.no_line = pno_line;

END IF;

END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_asignar_orden_lin` (IN `pno_order` VARCHAR(50), IN `pno_line` VARCHAR(50), IN `pno_locc` VARCHAR(50), IN `puser_work` VARCHAR(50), IN `pqty` VARCHAR(50), IN `puser_session` VARCHAR(50), IN `pindicador` VARCHAR(50))   BEGIN

declare vqty int;
declare vdispatched varchar(1) default 'N';
declare vstatus varchar(1);
declare vexists  int;

if pindicador = 'AI' THEN

update tbl_order_dtl 
set user_work = puser_work,
 	no_locc = pno_locc,
    modify_date = sysdate(),
    user_modify_date = puser_session
    where no_order = pno_order
    AND no_line = pno_line;

END IF;

if pindicador = 'LO' THEN

select qty into vqty from tbl_order_dtl where no_order = pno_order
    AND no_line = pno_line;

if vqty = pqty THEN
 set vdispatched = 'S';
end if;

update tbl_order_dtl 
set qty_dispatched = pqty,
	dispatched_flag = vdispatched,
    modify_date = sysdate(),
    user_modify_date = puser_session
    where no_order = pno_order
    AND no_line = pno_line;

select case when sum(qty) > sum(qty_dispatched) then 'A' else 'F' end
into vstatus 
from tbl_order_dtl
where no_order = pno_order;

update tbl_order
set status= vstatus,
    modify_date = sysdate(),
    user_modify_date = puser_session
    where no_order = pno_order;

select count(*)
into vexists 
from tbl_location_dtl d 
left JOIN tbl_order_dtl r ON d.no_locc = r.no_locc and d.prtnum = r.prtnum 
AND d.client_id = r.client_id
where r.no_order = pno_order AND r.no_line = pno_line;

update tbl_location_dtl d 
left JOIN tbl_order_dtl r ON d.no_locc = r.no_locc and d.prtnum = r.prtnum 
AND d.client_id = r.client_id
set d.qty = d.qty - pqty
where r.no_order = pno_order AND r.no_line = pno_line;


END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_clientes` (IN `pagina` INT)   begin

select * from tbl_client order by client_id LIMIT pagina, 5;

end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_clientes_all` ()   BEGIN
SELECT * FROM tbl_client;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_cliente_id` (IN `pclient_id` VARCHAR(50))   begin 

	select * from tbl_client where client_id = pclient_id;

end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_entradas` (IN `pagina` INT, IN `pclient_id` VARCHAR(50))   BEGIN

if pclient_id = '' then

	select r.no_receipt, r.client_id, r.country, r.city, r.description,r.status, 
    case 
    when r.status = 'R' then 'REGISTRANDO'
    when r.status = 'A' then 'PARCIAL'
    when r.status = 'F' then 'FINALIZADO'
    end descri_status, 
    date_format(r.date_receipt, '%Y-%m-%d') date_receipt, c.name descri_client 
    from tbl_receipt r 
    LEFT JOIN tbl_client c ON r.client_id = c.client_id
    ORDER BY no_receipt LIMIT pagina, 5;

else

	select r.no_receipt, r.client_id, r.country, r.city, r.description,r.status, 
    case 
    when r.status = 'R' then 'REGISTRANDO'
    when r.status = 'A' then 'PARCIAL'
    when r.status = 'F' then 'FINALIZADO'
    end descri_status, date_format(r.date_receipt, '%Y-%m-%d') date_receipt, c.name descri_client
    from tbl_receipt r 
    LEFT JOIN tbl_client c ON r.client_id = c.client_id
where r.client_id = pclient_id
    ORDER BY no_receipt LIMIT pagina, 5;

end if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_entradas_asignadas` ()   BEGIN

	select d.no_receipt, d.no_line, d.client_id, d.prtnum, d.lotnum,
    d.no_tempe, d.qty, ifnull(d.qty_located,0) qty_located, d.no_locc, d.user_work,
    p.name descri_prt, t.descri_tempe, c.name descri_client, l.descri_loc,
    d.located_flag,
     case 
    when e.status = 'R' then 'REGISTRANDO'
    when e.status = 'A' then 'PARCIAL'
    when e.status = 'F' then 'FINALIZADO'
    end descri_status
    from tbl_receipt_dtl d 
    LEFT JOIN tbl_product p ON d.prtnum = p.prtnum AND d.client_id = p.client_id
    LEFT JOIN tbl_temperature t ON d.no_tempe = t.no_tempe
    LEFT JOIN tbl_client c ON d.client_id = c.client_id
    LEFT JOIN tbl_location l ON d.no_locc = l.no_locc
    LEFT JOIN tbl_receipt e ON e.no_receipt = d.no_receipt
    where d.located_flag = 'N' AND ifnull(d.user_work,'') != ''  and ifnull(d.no_locc,'') != ''
    order by d.no_receipt;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_entradas_lineas` (IN `pno_receipt` VARCHAR(50))   BEGIN

	select d.no_receipt, d.no_line, d.client_id, d.prtnum, d.lotnum,
    d.no_tempe, d.qty, d.qty_located, d.no_locc, d.user_work,
    p.name descri_prt, t.descri_tempe, c.name descri_client, l.descri_loc
    from tbl_receipt_dtl d 
    LEFT JOIN tbl_product p ON d.prtnum = p.prtnum AND d.client_id = p.client_id
    LEFT JOIN tbl_temperature t ON d.no_tempe = t.no_tempe
    LEFT JOIN tbl_client c ON d.client_id = c.client_id
    LEFT JOIN tbl_location l ON d.no_locc = l.no_locc
    where d.no_receipt = pno_receipt;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_entradas_pendientes` ()   BEGIN

	select d.no_receipt, d.no_line, d.client_id, d.prtnum, d.lotnum,
    d.no_tempe, d.qty, d.qty_located, d.no_locc, d.user_work,
    p.name descri_prt, t.descri_tempe, c.name descri_client, l.descri_loc,
    d.located_flag,
     case 
    when e.status = 'R' then 'REGISTRANDO'
    when e.status = 'A' then 'PARCIAL'
    when e.status = 'F' then 'FINALIZADO'
    end descri_status
    from tbl_receipt_dtl d 
    LEFT JOIN tbl_product p ON d.prtnum = p.prtnum AND d.client_id = p.client_id
    LEFT JOIN tbl_temperature t ON d.no_tempe = t.no_tempe
    LEFT JOIN tbl_client c ON d.client_id = c.client_id
    LEFT JOIN tbl_location l ON d.no_locc = l.no_locc
    LEFT JOIN tbl_receipt e ON e.no_receipt = d.no_receipt
    where d.located_flag = 'N' AND e.status NOT IN ('F')
    order by d.no_receipt;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_entrada_id` (IN `pno_receipt` INT, IN `pclient_id` VARCHAR(50))   BEGIN

if pclient_id = '' then

	select r.no_receipt, r.client_id, r.country, r.city, r.description,r.status, 
    case 
    when r.status = 'R' then 'REGISTRANDO'
    when r.status = 'A' then 'PARCIAL'
    when r.status = 'F' then 'FINALIZADO'
    end descri_status,  date_format(r.date_receipt, '%Y-%m-%d') date_receipt, c.name descri_client 
    from tbl_receipt r 
    LEFT JOIN tbl_client c ON r.client_id = c.client_id
    WHERE no_receipt = pno_receipt;

else

	select r.no_receipt, r.client_id, r.country, r.city, r.description,r.status, 
    case 
    when r.status = 'R' then 'REGISTRANDO'
    when r.status = 'A' then 'PARCIAL'
    when r.status = 'F' then 'FINALIZADO'
    end descri_status,  date_format(r.date_receipt, '%Y-%m-%d') date_receipt, c.name descri_client
    from tbl_receipt r 
    LEFT JOIN tbl_client c ON r.client_id = c.client_id
    WHERE no_receipt = pno_receipt and client_id = pclient_id;

end if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_entrada_lin_id` (IN `pno_receipt` VARCHAR(50), IN `pno_line` VARCHAR(50))   BEGIN

select * from tbl_receipt_dtl where no_receipt = pno_receipt AND no_line = pno_line;

end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_location` (IN `pagina` INT)   begin

SELECT l.no_locc, l.descri_loc, l.capacity, l.client_id, c.name descri_client,
		l.full_flag, l.locc_log_flag, l.no_tempe, t.descri_tempe
FROM tbl_location l 
LEFT JOIN tbl_temperature t ON l.no_tempe = t.no_tempe
LEFT JOIN tbl_client c ON l.client_id = c.client_id
ORDER BY no_locc 
LIMIT pagina, 5;

end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_location_id` (IN `pno_locc` VARCHAR(50))   begin

select * from tbl_location where no_locc = pno_locc;

end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_ordenes` (IN `pagina` INT, IN `pclient_id` VARCHAR(15))   BEGIN

if pclient_id = '' then

	select r.no_order, r.client_id, r.description,r.status, 
    case 
    when r.status = 'R' then 'REGISTRANDO'
    when r.status = 'A' then 'PARCIAL'
    when r.status = 'F' then 'FINALIZADO'
    end descri_status, 
    date_format(r.date_dispatched, '%Y-%m-%d') date_dispatched, c.name descri_client 
    from tbl_order r 
    LEFT JOIN tbl_client c ON r.client_id = c.client_id
    ORDER BY no_order LIMIT pagina, 5;

else

	select r.no_order, r.client_id, r.description,r.status, 
    case 
    when r.status = 'R' then 'REGISTRANDO'
    when r.status = 'A' then 'PARCIAL'
    when r.status = 'F' then 'FINALIZADO'
    end descri_status, 
    date_format(r.date_dispatched, '%Y-%m-%d') date_dispatched, c.name descri_client 
    from tbl_order r 
    LEFT JOIN tbl_client c ON r.client_id = c.client_id
    where r.client_id = pclient_id
    ORDER BY no_order LIMIT pagina, 5;

end if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_ordenes_asignadas` ()   BEGIN

	select d.no_order, d.no_line, d.client_id, d.prtnum, d.lotnum,
    d.qty, ifnull(d.qty_dispatched,0) qty_dispatched, d.no_locc, d.user_work,
    p.name descri_prt, c.name descri_client, l.descri_loc,
    dispatched_flag,
     case 
    when e.status = 'R' then 'REGISTRANDO'
    when e.status = 'A' then 'PARCIAL'
    when e.status = 'F' then 'FINALIZADO'
    end descri_status
    from tbl_order_dtl d 
    LEFT JOIN tbl_product p ON d.prtnum = p.prtnum AND d.client_id = p.client_id
    LEFT JOIN tbl_client c ON d.client_id = c.client_id
    LEFT JOIN tbl_location l ON d.no_locc = l.no_locc
    LEFT JOIN tbl_order e ON e.no_order = d.no_order
    where d.dispatched_flag = 'N' AND ifnull(d.user_work,'') != ''  and ifnull(d.no_locc,'') != ''
    order by d.no_order;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_ordenes_id` (IN `pno_order` VARCHAR(50), IN `pclient_id` VARCHAR(50))   begin
if pclient_id = '' then

	select r.no_order, r.client_id, r.description,r.status, 
    case 
    when r.status = 'R' then 'REGISTRANDO'
    when r.status = 'A' then 'PARCIAL'
    when r.status = 'F' then 'FINALIZADO'
    end descri_status, 
    date_format(r.date_dispatched, '%Y-%m-%d') date_dispatched, c.name descri_client 
    from tbl_order r 
    LEFT JOIN tbl_client c ON r.client_id = c.client_id
    WHERE r.no_order = pno_order
    ORDER BY r.no_order;

else

	select r.no_order, r.client_id, r.description,r.status, 
    case 
    when r.status = 'R' then 'REGISTRANDO'
    when r.status = 'A' then 'PARCIAL'
    when r.status = 'F' then 'FINALIZADO'
    end descri_status, 
    date_format(r.date_dispatched, '%Y-%m-%d') date_dispatched, c.name descri_client 
    from tbl_order r 
    LEFT JOIN tbl_client c ON r.client_id = c.client_id
    where r.client_id = pclient_id and r.no_order = pno_order
    ORDER BY r.no_order;

end if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_ordenes_lineas` (IN `pno_order` VARCHAR(50))   BEGIN

	select d.no_order, d.no_line, d.client_id, d.prtnum, d.lotnum,
    d.qty, d.qty_dispatched, d.no_locc, d.user_work,
    p.name descri_prt, c.name descri_client, l.descri_loc
    from tbl_order_dtl d 
    LEFT JOIN tbl_product p ON d.prtnum = p.prtnum AND d.client_id = p.client_id
    LEFT JOIN tbl_client c ON d.client_id = c.client_id
    LEFT JOIN tbl_location l ON d.no_locc = l.no_locc
    where d.no_order = pno_order;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_ordenes_pendientes` ()   BEGIN
	select d.no_order, d.no_line, d.client_id, d.prtnum, d.lotnum,
    d.qty, ifnull(d.qty_dispatched, 0) qty_dispatched, d.no_locc, d.user_work,
    p.name descri_prt, c.name descri_client, l.descri_loc,
    d.dispatched_flag,
     case 
    when e.status = 'R' then 'REGISTRANDO'
    when e.status = 'A' then 'PARCIAL'
    when e.status = 'F' then 'FINALIZADO'
    end descri_status
    from tbl_order_dtl d 
    LEFT JOIN tbl_product p ON d.prtnum = p.prtnum AND d.client_id = p.client_id
    LEFT JOIN tbl_client c ON d.client_id = c.client_id
    LEFT JOIN tbl_location l ON d.no_locc = l.no_locc
    LEFT JOIN tbl_order e ON e.no_order = d.no_order
    where d.dispatched_flag = 'N' AND e.status NOT IN ('F')
    order by d.no_order;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_order_lin_id` (IN `pno_order` VARCHAR(50), IN `pno_line` VARCHAR(50))   BEGIN

select * from tbl_order_dtl where no_order = pno_order AND no_line = pno_line;

end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_producto` (IN `pagina` INT)   begin

SELECT p.prtnum, p.lotnum, p.client_id, c.name as descri_client, p.name,
		p.description, date_format(p.date_manufacture, '%Y-%m-%d') as date_manufacture, 
        date_format(p.date_expirated, '%Y-%m-%d') as date_expirated
FROM tbl_product p 
LEFT JOIN tbl_client c ON p.client_id = c.client_id
ORDER BY prtnum 
LIMIT pagina, 5;

end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_producto_all` (IN `pclient_id` VARCHAR(50))   BEGIN

if pclient_id = "" THEN

	select * from tbl_product;

ELSE

	select * from tbl_product where client_id = pclient_id;

END if;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_producto_id` (IN `pprtnum` VARCHAR(50))   BEGIN

select prtnum, lotnum, client_id, name, description,
 date_format(date_manufacture, '%Y-%m-%d') date_manufacture, date_format(date_expirated, '%Y-%m-%d') date_expirated from tbl_product where prtnum = pprtnum;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_temperaturas_all` ()   BEGIN
SELECT * FROM tbl_temperature;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_ubicaciones_dis` (IN `pno_order` VARCHAR(50), IN `pno_line` VARCHAR(50))   begin

select lc.no_locc, lc.descri_loc, ifnull(SUM(ld.qty),0) qty_located,  rd.qty
FROM tbl_location lc
LEFT JOIN tbl_location_dtl ld ON lc.no_locc = ld.no_locc
INNER JOIN tbl_order_dtl rd ON rd.no_order = pno_order AND rd.no_line = pno_line and ld.prtnum = rd.prtnum AND lc.client_id = rd.client_id
GROUP BY ld.no_locc, ld.prtnum
having (qty_located) >= rd.qty;

end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_ubicaciones_rep` (IN `pno_receipt` VARCHAR(50), IN `pno_line` VARCHAR(50))   BEGIN 

SELECT lc.no_locc, lc.descri_loc, lc.capacity, ifnull(SUM(ld.qty),0) qty_located, rd.qty
FROM tbl_location lc
LEFT JOIN tbl_location_dtl ld ON lc.no_locc = ld.no_locc
AND lc.client_id = ld.client_id
INNER JOIN tbl_receipt_dtl rd ON rd.no_receipt = pno_receipt AND rd.no_line = pno_line and lc.no_tempe = rd.no_tempe
AND lc.client_id = rd.client_id
GROUP BY lc.no_locc
having (lc.capacity - qty_located) >= rd.qty;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_usuarios` (IN `pagina` INT)   begin

select * from tbl_user order by user LIMIT pagina, 5;

end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consultar_usuario_id` (IN `puser` VARCHAR(50))   BEGIN

select * from tbl_user where user = puser;

end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consutar_user_dispatcher` ()   begin 

select * from tbl_user where dispatcher_flag = 'S';

end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_consutar_user_receiver` ()   begin 

select * from tbl_user where receiver_flag = 'S';

end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_conteo_clientes` ()   BEGIN

select count(*) cantidad from tbl_client;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_conteo_entradas` (IN `pclient_id` VARCHAR(50))   BEGIN

if pclient_id = '' then

	select count(*) cantidad from tbl_receipt;

else

	select count(*) cantidad from tbl_receipt where client_id = pclient_id;

end if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_conteo_location` ()   BEGIN

select count(*) cantidad from tbl_location;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_conteo_ordenes` (IN `pclient_id` VARCHAR(15))   BEGIN

if pclient_id = '' then

	select count(*) cantidad from tbl_order;

else

	select count(*) cantidad from tbl_order where client_id = pclient_id;

end if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_conteo_producto` ()   BEGIN

select count(*) cantidad from tbl_product;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_conteo_usuarios` ()   BEGIN

select count(*) cantidad from tbl_user;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_guardar_client` (IN `pclient_id` VARCHAR(50), IN `pname` VARCHAR(200), IN `pidentification` VARCHAR(15), IN `paddres` VARCHAR(200), IN `pcountry` VARCHAR(50), IN `pcity` VARCHAR(50), IN `puser_session` VARCHAR(50), IN `pindicador` VARCHAR(50))   BEGIN

declare vexists INT;
declare vsecuencia INT;

if pindicador = "I" THEN

select count(*) into vexists from tbl_client where client_id = pclient_id;

if vexists = 0 THEN

select ifnull(max(client_id), 0) + 1  into vsecuencia from tbl_client;

insert into tbl_client (client_id, name, addres, country, city, identification, add_date, user_add_date)
                     values (vsecuencia , pname, paddres, pcountry, pcity, pidentification, sysdate(), puser_session);

ELSE

update tbl_client
set name = pname,
    addres = paddres,
    country = pcountry,
    city = pcity,
    identification = pidentification,
    modify_date = sysdate(),
    user_modify_date = puser_session
where client_id = pclient_id;

end if;

end if;

if pindicador = "D" THEN

delete from tbl_client where client_id = pclient_id;

end if;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_guardar_entrada` (IN `pno_receipt` VARCHAR(50), IN `pclient_id` VARCHAR(50), IN `pcountry` VARCHAR(50), IN `pcity` VARCHAR(50), IN `pdescription` VARCHAR(200), IN `pdate_receipt` VARCHAR(50), IN `puser_session` VARCHAR(50), IN `pindicador` VARCHAR(50))   BEGIN

declare vexists INT;
declare vsecuencia INT;

if pindicador = "I" THEN

select count(*) into vexists from tbl_receipt where no_receipt = pno_receipt;

if vexists = 0 THEN

select ifnull(max(no_receipt), 0) + 1  into vsecuencia from tbl_receipt;

insert into tbl_receipt (no_receipt, client_id, country, city, description, date_receipt, status,
                         add_date, user_add_date)
                     values (vsecuencia, pclient_id, pcountry, pcity, pdescription, pdate_receipt, 'R',
                             sysdate(), puser_session);

ELSE

update tbl_receipt
set client_id = pclient_id,
    country = pcountry,
    city = pcity,
    description = pdescription,
    date_receipt = pdate_receipt,
    modify_date = sysdate(),
    user_modify_date = puser_session
where no_receipt = pno_receipt;

end if;

end if;

if pindicador = "D" THEN

delete from tbl_receipt_dtl where no_receipt = pno_receipt;

delete from tbl_receipt where no_receipt = pno_receipt;

end if;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_guardar_entrada_lin` (IN `pno_receipt` VARCHAR(50), IN `pno_line` VARCHAR(50), IN `pqty` INT, IN `pclient_id` VARCHAR(50), IN `pprtnum` VARCHAR(50), IN `pno_tempe` VARCHAR(50), IN `puser_session` VARCHAR(50), IN `pindicador` VARCHAR(50))   BEGIN

declare vexists INT;
declare vsecuencia INT;

if pindicador = "LI" THEN

select count(*) into vexists from tbl_receipt_dtl where no_receipt = pno_receipt and no_line = pno_line;

if vexists = 0 THEN

select ifnull(max(no_line), 0) + 1  into vsecuencia from tbl_receipt_dtl where no_receipt = pno_receipt;

insert into tbl_receipt_dtl (no_line, no_receipt, client_id, prtnum, qty, no_tempe, located_flag,
                         add_date, user_add_date)
                     values (vsecuencia, pno_receipt, pclient_id, pprtnum, pqty, pno_tempe, 'N',
                             sysdate(), puser_session);

ELSE

update tbl_receipt_dtl
set qty = pqty,
	no_tempe = pno_tempe,
    modify_date = sysdate(),
    user_modify_date = puser_session
where no_receipt = pno_receipt and no_line = pno_line;

end if;

end if;

if pindicador = "LD" THEN

delete from tbl_receipt_dtl where no_receipt = pno_receipt and no_line = pno_line;
end if;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_guardar_location` (IN `pno_locc` VARCHAR(50), IN `pdescri_loc` VARCHAR(200), IN `pcapacity` INT, IN `pno_tempe` INT, IN `pclient_id` INT, IN `puser_session` VARCHAR(50), IN `pindicador` VARCHAR(50))   BEGIN

declare vexists INT;

if pindicador = "I" THEN

select count(*) into vexists from tbl_location where no_locc = pno_locc;

if vexists = 0 THEN

insert into tbl_location (no_locc, descri_loc, capacity, client_id, no_tempe, full_flag,
                     add_date, user_add_date)
                     values (pno_locc, pdescri_loc, pcapacity, pclient_id, pno_tempe, 'N',
                     sysdate(), puser_session);

ELSE

update tbl_location
set descri_loc = pdescri_loc,
    modify_date = sysdate(),
    user_modify_date = puser_session
where no_locc = pno_locc;

end if;

end if;

if pindicador = "D" THEN

delete from tbl_location where no_locc = pno_locc;

end if;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_guardar_orden` (IN `pno_order` VARCHAR(50), IN `pclient_id` VARCHAR(50), IN `pdescription` VARCHAR(200), IN `pdate_dispatched` VARCHAR(50), IN `puser_session` VARCHAR(50), IN `pindicador` VARCHAR(50))   BEGIN

declare vexists INT;
declare vsecuencia INT;

if pindicador = "I" THEN

select count(*) into vexists from tbl_order where no_order = pno_order;

if vexists = 0 THEN

select ifnull(max(no_order), 0) + 1  into vsecuencia from tbl_order;

insert into tbl_order (no_order, client_id, description, date_dispatched, status,
                         add_date, user_add_date)
                     values (vsecuencia, pclient_id, pdescription, pdate_dispatched, 'R',
                             sysdate(), puser_session);

ELSE

update tbl_order
set client_id = pclient_id,
    description = pdescription,
    modify_date = sysdate(),
    user_modify_date = puser_session
where no_order = pno_order;

end if;

end if;

if pindicador = "D" THEN

delete from tbl_order_dtl where no_order = pno_order;

delete from tbl_order where no_order = pno_order;

end if;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_guardar_order_lin` (IN `pno_order` VARCHAR(50), IN `pno_line` VARCHAR(50), IN `pqty` INT, IN `pclient_id` VARCHAR(50), IN `pprtnum` VARCHAR(50), IN `puser_session` VARCHAR(50), IN `pindicador` VARCHAR(50))   BEGIN

declare vexists INT;
declare vsecuencia INT;

if pindicador = "LI" THEN

select count(*) into vexists from tbl_order_dtl where no_order = pno_order and no_line = pno_line;

if vexists = 0 THEN

select ifnull(max(no_line), 0) + 1  into vsecuencia from tbl_order_dtl where no_order = pno_order;

insert into tbl_order_dtl (no_line, no_order, client_id, prtnum, qty, dispatched_flag,
                         add_date, user_add_date)
                     values (vsecuencia, pno_order, pclient_id, pprtnum, pqty, 'N',
                             sysdate(), puser_session);

ELSE

update tbl_order_dtl
set qty = pqty,
    modify_date = sysdate(),
    user_modify_date = puser_session
where no_order = pno_order and no_line = pno_line;

end if;

end if;

if pindicador = "LD" THEN

delete from tbl_order_dtl where no_order = pno_order and no_line = pno_line;
end if;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_guardar_producto` (IN `pprtnum` VARCHAR(50), IN `pname` VARCHAR(100), IN `pdescription` VARCHAR(200), IN `plotnum` VARCHAR(50), IN `pclient_id` INT, IN `pdate_manufacture` VARCHAR(50), IN `pdate_expirated` VARCHAR(50), IN `puser_session` VARCHAR(50), IN `pindicador` VARCHAR(50))   BEGIN

declare vexists INT;

if pindicador = "I" THEN

select count(*) into vexists from tbl_product where prtnum = pprtnum;

if vexists = 0 THEN

insert into tbl_product (prtnum, lotnum, client_id, name, description,
                         date_manufacture, date_expirated,
                     add_date, user_add_date)
                     values (pprtnum, plotnum, pclient_id, pname, pdescription, 
                             pdate_manufacture, pdate_expirated,
                     sysdate(), puser_session);

ELSE

update tbl_product
set name = pname,
  	description = pdescription,
    date_manufacture = pdate_manufacture,
    date_expirated = pdate_expirated,
    modify_date = sysdate(),
    user_modify_date = puser_session
where prtnum = pprtnum
and lotnum = plotnum
and client_id = pclient_id;

end if;

end if;

if pindicador = "D" THEN

delete from tbl_product where 
prtnum = pprtnum
and lotnum = plotnum
and client_id = pclient_id;

end if;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_guardar_usuario` (IN `puser` VARCHAR(50), IN `pfirts_name` VARCHAR(50), IN `plast_name` VARCHAR(50), IN `ppassword` VARCHAR(50), IN `padmin_flag` VARCHAR(50), IN `preceiver_flag` VARCHAR(50), IN `pdispatcher_flag` VARCHAR(50), IN `pclient_flag` VARCHAR(50), IN `pclient_id` VARCHAR(50), IN `puser_session` VARCHAR(50), IN `pindicador` VARCHAR(50))   BEGIN

declare vexists INT;

if pindicador = "I" THEN

select count(*) into vexists from tbl_user where user = puser;

if vexists = 0 THEN

insert into tbl_user (user, firts_name, last_name, password, admin_flag, receiver_flag, dispatcher_flag, client_flag, client_id,
                     add_date, user_add_date)
                     values (puser, pfirts_name, plast_name, ppassword, padmin_flag, preceiver_flag, pdispatcher_flag, client_flag,
                            pclient_id, sysdate(), puser_session);

ELSE

update tbl_user
set firts_name = pfirts_name, 
    last_name = plast_name, 
    admin_flag = padmin_flag, 
    receiver_flag = preceiver_flag, 
    dispatcher_flag = pdispatcher_flag,  
    client_flag = pclient_flag, 
    client_id = pclient_id,
    modify_date = sysdate(),
    user_modify_date = puser_session
where user = puser;

if ppassword <> '' then
update tbl_user
set password = ppassword
where user = puser;
end if;

end if;

end if;

if pindicador = "D" THEN

delete from tbl_user where user = puser;

end if;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_import` (IN `pclient_id` VARCHAR(50))   BEGIN

if pclient_id = "" then

select rd.no_receipt, rd.no_line, rd.qty, ifnull(rd.qty_located,0) qty_located, rc.status,
case 
    when rc.status = 'R' then 'REGISTRANDO'
    when rc.status = 'A' then 'PARCIAL'
    when rc.status = 'F' then 'FINALIZADO'
    end descri_status, rd.located_flag, c.name descri_client
from tbl_receipt_dtl rd
inner join tbl_receipt rc ON rd.no_receipt = rc.no_receipt
LEFT JOIN tbl_client c ON c.client_id = rd.client_id
order by rd.no_receipt, rd.no_line;

else 

select rd.no_receipt, rd.no_line, rd.qty, ifnull(rd.qty_located,0) qty_located, rc.status,
case 
    when rc.status = 'R' then 'REGISTRANDO'
    when rc.status = 'A' then 'PARCIAL'
    when rc.status = 'F' then 'FINALIZADO'
    end descri_status, rd.located_flag, c.name descri_client
from tbl_receipt_dtl rd
inner join tbl_receipt rc ON rd.no_receipt = rc.no_receipt
LEFT JOIN tbl_client c ON c.client_id = rd.client_id
where rd.client_id = pclient_id
order by rd.no_receipt, rd.no_line;

end if;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_report_inventory_avaible` (IN `pclient_id` VARCHAR(50))   BEGIN

if pclient_id != '' then

select ld.prtnum, p.name, ld.client_id, c.name descri_client, ld.no_locc, 
	t.descri_tempe, ld.qty, lc.descri_loc
FROM tbl_location_dtl ld
LEFT JOIN tbl_location lc ON lc.no_locc = ld.no_locc
LEFT JOIN tbl_product p on ld.prtnum = p.prtnum and ld.client_id = p.client_id
LEFT JOIN tbl_client c on c.client_id = ld.client_id 
LEFT JOIN tbl_temperature t on t.no_tempe = lc.no_tempe
where ld.client_id = pclient_id
ORDER BY ld.client_id, ld.prtnum;

else

select ld.prtnum, p.name, ld.client_id, c.name descri_client, ld.no_locc, 
	t.descri_tempe, ld.qty, lc.descri_loc
FROM tbl_location_dtl ld
LEFT JOIN tbl_location lc ON lc.no_locc = ld.no_locc
LEFT JOIN tbl_product p on ld.prtnum = p.prtnum and ld.client_id = p.client_id
LEFT JOIN tbl_client c on c.client_id = ld.client_id 
LEFT JOIN tbl_temperature t on t.no_tempe = lc.no_tempe
ORDER BY ld.client_id, ld.prtnum;

end if;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_validar_usuario` (`pusuario` VARCHAR(50), `pclave` VARCHAR(500))   BEGIN 

select * from tbl_user where tbl_user.user = pusuario and password = pclave;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_client`
--

CREATE TABLE `tbl_client` (
  `client_id` int(11) NOT NULL,
  `country` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `addres` varchar(200) DEFAULT NULL,
  `identification` varchar(15) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `user_add_date` varchar(50) DEFAULT NULL,
  `user_modify_date` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_client`
--

INSERT INTO `tbl_client` (`client_id`, `country`, `city`, `name`, `addres`, `identification`, `add_date`, `modify_date`, `user_add_date`, `user_modify_date`) VALUES
(1, 'PANAMÁ', 'PANAMÁ', 'PFIZER', 'CIUDAD DE PANAMÁ', '8006130', '2022-12-11 21:14:27', NULL, 'testuser', NULL),
(2, 'PANAMÁ', 'PANAMÁ', 'ZOETIS', 'CIUDAD DE PANAMÁ', '2151454-1-2025', '2022-12-11 21:14:58', NULL, 'testuser', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_location`
--

CREATE TABLE `tbl_location` (
  `no_locc` varchar(15) NOT NULL,
  `descri_loc` varchar(200) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `no_tempe` int(11) DEFAULT NULL,
  `full_flag` varchar(1) DEFAULT NULL,
  `locc_log_flag` varchar(1) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `user_add_date` varchar(50) DEFAULT NULL,
  `user_modify_date` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_location`
--

INSERT INTO `tbl_location` (`no_locc`, `descri_loc`, `capacity`, `client_id`, `no_tempe`, `full_flag`, `locc_log_flag`, `add_date`, `modify_date`, `user_add_date`, `user_modify_date`) VALUES
('F1-P2-A1', 'Productos genericos', 200, 1, 4, 'N', NULL, '2022-12-12 15:36:22', NULL, 'testuser', NULL),
('F1-P3-A1', 'PRODUCTOS FRÍOS', 100, 1, 3, 'N', NULL, '2022-12-11 21:16:45', NULL, 'testuser', NULL),
('R1-P1-A1', 'Estante para productos en temperatura ambiente', 50, 1, 5, 'N', NULL, '2022-12-11 21:15:43', NULL, 'testuser', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_location_dtl`
--

CREATE TABLE `tbl_location_dtl` (
  `no_locc` varchar(15) NOT NULL,
  `prtnum` varchar(50) NOT NULL,
  `lotnum` varchar(50) NOT NULL,
  `qty` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `user_add_date` varchar(50) DEFAULT NULL,
  `user_modify_date` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_location_dtl`
--

INSERT INTO `tbl_location_dtl` (`no_locc`, `prtnum`, `lotnum`, `qty`, `client_id`, `add_date`, `modify_date`, `user_add_date`, `user_modify_date`) VALUES
('R1-P1-A1', 'AF-01-22', 'LOT12345', 18, 1, '2022-12-12 13:58:38', NULL, 'testuser', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_order`
--

CREATE TABLE `tbl_order` (
  `no_order` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `date_order` datetime DEFAULT NULL,
  `date_dispatched` datetime DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `user_add_date` varchar(50) DEFAULT NULL,
  `user_modify_date` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_order`
--

INSERT INTO `tbl_order` (`no_order`, `client_id`, `description`, `status`, `date_order`, `date_dispatched`, `add_date`, `modify_date`, `user_add_date`, `user_modify_date`) VALUES
(1, 1, 'pedido de alfen', 'F', NULL, '2022-12-20 00:00:00', '2022-12-12 16:35:36', '2022-12-12 18:07:41', 'testclient', 'testsalida');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_order_dtl`
--

CREATE TABLE `tbl_order_dtl` (
  `no_order` int(11) NOT NULL,
  `no_line` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `prtnum` varchar(50) DEFAULT NULL,
  `lotnum` varchar(50) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `qty_dispatched` int(11) DEFAULT NULL,
  `no_locc` varchar(50) DEFAULT NULL,
  `dispatched_flag` varchar(1) DEFAULT NULL,
  `user_work` varchar(50) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `user_add_date` varchar(50) DEFAULT NULL,
  `user_modify_date` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_order_dtl`
--

INSERT INTO `tbl_order_dtl` (`no_order`, `no_line`, `client_id`, `prtnum`, `lotnum`, `qty`, `qty_dispatched`, `no_locc`, `dispatched_flag`, `user_work`, `add_date`, `modify_date`, `user_add_date`, `user_modify_date`) VALUES
(1, 1, 1, 'AF-01-22', NULL, 7, 7, 'R1-P1-A1', 'S', 'testsalida', '2022-12-12 17:08:24', '2022-12-12 18:07:41', 'testclient', 'testsalida');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_product`
--

CREATE TABLE `tbl_product` (
  `prtnum` varchar(50) NOT NULL,
  `lotnum` varchar(50) NOT NULL,
  `client_id` varchar(50) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `date_manufacture` datetime DEFAULT NULL,
  `date_expirated` datetime DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `user_add_date` varchar(50) DEFAULT NULL,
  `user_modify_date` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_product`
--

INSERT INTO `tbl_product` (`prtnum`, `lotnum`, `client_id`, `name`, `description`, `date_manufacture`, `date_expirated`, `add_date`, `modify_date`, `user_add_date`, `user_modify_date`) VALUES
('AF-01-22', 'LOT12345', '1', 'ALFENTANIL', 'Alfentanilo 500 microgramos / ml solución inyectable', '2022-02-08 00:00:00', '2023-07-28 00:00:00', '2022-12-11 21:19:28', NULL, 'testuser', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_receipt`
--

CREATE TABLE `tbl_receipt` (
  `no_receipt` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `country` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `date_receipt` datetime DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `user_add_date` varchar(50) DEFAULT NULL,
  `user_modify_date` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_receipt`
--

INSERT INTO `tbl_receipt` (`no_receipt`, `client_id`, `country`, `city`, `description`, `status`, `date_receipt`, `add_date`, `modify_date`, `user_add_date`, `user_modify_date`) VALUES
(1, 1, 'PANAMÁ', 'PANAMÁ', 'PEDIDO DE INYECCIONES', 'A', '2022-12-14 00:00:00', '2022-12-11 22:47:52', '2022-12-12 13:58:38', 'testclient', 'testuser'),
(2, 2, 'PANAMÁ', 'PANAMÁ', 'PEDIDO DE ZOETIS', 'R', '2022-12-27 00:00:00', '2022-12-12 01:21:38', '2022-12-12 01:21:52', 'testclient', 'testclient'),
(3, 1, 'PANAMÁ', 'PANAMÁ', 'Pedido de inyecciones alfentanil', 'R', '2022-12-12 00:00:00', '2022-12-12 16:20:14', '2022-12-12 16:33:02', 'testclient', 'testclient'),
(4, 1, 'PANAMA', 'panama', 'prueba', 'F', '2022-12-28 00:00:00', '2022-12-12 16:33:21', '2022-12-12 17:48:22', 'testclient', 'testentrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_receipt_dtl`
--

CREATE TABLE `tbl_receipt_dtl` (
  `no_receipt` int(11) NOT NULL,
  `no_line` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `prtnum` varchar(50) NOT NULL,
  `lotnum` varchar(50) NOT NULL,
  `no_tempe` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `qty_located` int(11) DEFAULT NULL,
  `no_locc` varchar(15) DEFAULT NULL,
  `located_flag` varchar(1) DEFAULT NULL,
  `user_work` varchar(50) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `user_add_date` varchar(50) DEFAULT NULL,
  `user_modify_date` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_receipt_dtl`
--

INSERT INTO `tbl_receipt_dtl` (`no_receipt`, `no_line`, `client_id`, `prtnum`, `lotnum`, `no_tempe`, `qty`, `qty_located`, `no_locc`, `located_flag`, `user_work`, `add_date`, `modify_date`, `user_add_date`, `user_modify_date`) VALUES
(0, 1, 1, 'AF-01-22', '', 5, 30, NULL, NULL, 'N', NULL, '2022-12-12 17:05:43', NULL, 'testclient', NULL),
(1, 1, 1, 'AF-01-22', '', 5, 15, 15, 'R1-P1-A1', 'S', 'testentrada', '2022-12-12 01:17:08', '2022-12-12 13:58:38', 'testclient', 'testuser'),
(1, 2, 1, 'AF-01-22', '', 4, 10, NULL, 'F1-P2-A1', 'N', 'testentrada', '2022-12-12 01:20:37', '2022-12-12 15:40:53', 'testclient', 'testuser'),
(4, 1, 1, 'AF-01-22', '', 5, 10, 10, 'R1-P1-A1', 'S', 'testentrada', '2022-12-12 17:08:43', '2022-12-12 17:48:21', 'testclient', 'testentrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_temperature`
--

CREATE TABLE `tbl_temperature` (
  `no_tempe` int(11) NOT NULL,
  `descri_tempe` varchar(50) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `user_add_date` varchar(50) DEFAULT NULL,
  `user_modify_date` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_temperature`
--

INSERT INTO `tbl_temperature` (`no_tempe`, `descri_tempe`, `add_date`, `modify_date`, `user_add_date`, `user_modify_date`) VALUES
(1, 'Almacenamiento en congelador: -25°C – 10°C', '2022-12-11 17:18:15', NULL, 'testuser', NULL),
(2, 'Refrigerador Almacenamiento: 2°C – 8°C', '2022-12-11 17:18:16', NULL, 'testuser', NULL),
(3, 'Almacenamiento en frío: 8°C – 15°C', '2022-12-11 17:18:16', NULL, 'testuser', NULL),
(4, 'Temperatura ambiente controlada: 20°C – 25°C', '2022-12-11 17:18:16', NULL, 'testuser', NULL),
(5, 'Almacenamiento a Temperatura Ambiente: 20°C – 25°C', '2022-12-11 17:18:17', NULL, 'testuser', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user` varchar(50) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `firts_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `admin_flag` varchar(1) DEFAULT NULL,
  `receiver_flag` varchar(1) DEFAULT NULL,
  `dispatcher_flag` varchar(1) DEFAULT NULL,
  `client_flag` varchar(1) DEFAULT NULL,
  `client_id` varchar(50) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `modify_date` datetime DEFAULT NULL,
  `user_add_date` varchar(50) DEFAULT NULL,
  `user_modify_date` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_user`
--

INSERT INTO `tbl_user` (`user`, `password`, `firts_name`, `last_name`, `admin_flag`, `receiver_flag`, `dispatcher_flag`, `client_flag`, `client_id`, `add_date`, `modify_date`, `user_add_date`, `user_modify_date`) VALUES
('testuser', 'teXB5LK3JWG6g', 'Juan', 'Stephens', 'S', NULL, NULL, NULL, NULL, '2022-11-30 08:24:44', NULL, 'testuser', NULL),
('testclient', 'te7reyUq9TCQY', 'roberto (pfizer)23', '', 'N', 'N', 'N', 'S', '1', '2022-12-04 09:27:21', '2022-12-11 21:23:47', 'testuser', 'testuser'),
('testentrada', 'tewdBsxPs7QKU', 'alberto', 'castillo', 'N', 'S', 'N', NULL, '', '2022-12-12 12:28:54', NULL, 'testuser', NULL),
('testsalida', 'te7NdUOQuP8sI', 'Gerardo', 'Gonzalez', 'N', 'N', 'S', NULL, '', '2022-12-12 17:47:41', NULL, 'testuser', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbl_client`
--
ALTER TABLE `tbl_client`
  ADD PRIMARY KEY (`client_id`);

--
-- Indices de la tabla `tbl_location`
--
ALTER TABLE `tbl_location`
  ADD PRIMARY KEY (`no_locc`,`client_id`);

--
-- Indices de la tabla `tbl_location_dtl`
--
ALTER TABLE `tbl_location_dtl`
  ADD PRIMARY KEY (`no_locc`,`prtnum`,`lotnum`,`client_id`);

--
-- Indices de la tabla `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`no_order`,`client_id`);

--
-- Indices de la tabla `tbl_order_dtl`
--
ALTER TABLE `tbl_order_dtl`
  ADD PRIMARY KEY (`no_order`,`no_line`,`client_id`);

--
-- Indices de la tabla `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`prtnum`,`lotnum`,`client_id`);

--
-- Indices de la tabla `tbl_receipt`
--
ALTER TABLE `tbl_receipt`
  ADD PRIMARY KEY (`no_receipt`,`client_id`);

--
-- Indices de la tabla `tbl_receipt_dtl`
--
ALTER TABLE `tbl_receipt_dtl`
  ADD PRIMARY KEY (`no_receipt`,`no_line`,`client_id`,`prtnum`,`lotnum`);

--
-- Indices de la tabla `tbl_temperature`
--
ALTER TABLE `tbl_temperature`
  ADD PRIMARY KEY (`no_tempe`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_temperature`
--
ALTER TABLE `tbl_temperature`
  MODIFY `no_tempe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
