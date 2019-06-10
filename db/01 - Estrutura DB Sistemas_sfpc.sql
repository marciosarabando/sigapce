-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 10-Jun-2019 às 11:58
-- Versão do servidor: 5.5.55-0+deb8u1
-- PHP Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sistemas_sfpc`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`sistemas_sfpc`@`%` PROCEDURE `apaga_agendado_sem_interessado`()
BEGIN


DECLARE done INT DEFAULT 0;
DECLARE var_id_agendamento_requerente_andamento INT ;
DECLARE var_id_agendamento_requerente INT ;
DECLARE var_id_agendamento_horario INT ;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;



BEGIN
DECLARE c_agendamento CURSOR FOR SELECT 
    ara.id_agendamento_requerente_andamento,
    ar.id_agendamento_requerente,
    ar.id_agendamento_horario
FROM
    agendamento_requerente_andamento ara,
    agendamento_requerente ar,
    agendamento_login al,
    (SELECT 
        MAX(id_agendamento_requerente_andamento) AS id_agendamento_requerente_andamento
    FROM
        agendamento_requerente_andamento
    GROUP BY id_agendamento_requerente) base
WHERE
    base.id_agendamento_requerente_andamento = ara.id_agendamento_requerente_andamento
        AND ara.id_agendamento_requerente = ar.id_agendamento_requerente
        AND al.id_agendamento_login = ar.id_agendamento_login
        AND id_agendamento_login_tipo = 2
        AND id_agendamento_status = 1
        AND dt_agendamento_requerente_andamento >= '2019-06-12 00:00:01'
		AND dt_agendamento_requerente_andamento <= now() - (select vl_parametro from parametro where nm_parametro = 'time_out_agendamento')
        AND NOT EXISTS( SELECT 
            1
        FROM
            agendamento_horario_interessado ahi
        WHERE
            ar.id_agendamento_horario = ahi.id_agendamento_horario
                AND ar.id_agendamento_login = ahi.id_agendamento_login) group by ara.id_agendamento_requerente_andamento,
    ar.id_agendamento_requerente,
    ar.id_agendamento_horario; 
  

open c_agendamento;

read_loop: LOOP

FETCH  c_agendamento into var_id_agendamento_requerente_andamento, var_id_agendamento_requerente, var_id_agendamento_horario;





IF (done = 1) THEN
          LEAVE read_loop;
         ELSE
          
          delete from agendamento_requerente_andamento where id_agendamento_requerente_andamento = var_id_agendamento_requerente_andamento;
          
		  delete from agendamento_requerente where id_agendamento_requerente = var_id_agendamento_requerente and  id_agendamento_horario = var_id_agendamento_horario;
          
          update agendamento_horario set qt_requerente_agendado = qt_requerente_agendado -1 where id_agendamento_horario = var_id_agendamento_horario ;
          
        END IF;



END LOOP;

set done = 0;


END;




end$$

CREATE DEFINER=`sistemas_sfpc`@`%` PROCEDURE `gera_media_serv`(in INICIO date, in FIM date, in UNIDADE varchar(3), in AMOSTRAGEM varchar(5), CARTEIRA int(3), SERVICO int(3), TIPO_CLI varchar(5))
BEGIN


DECLARE done INT DEFAULT 0;
DECLARE count1 INT DEFAULT 0;
DECLARE count2 INT DEFAULT 0;
DECLARE count3 INT DEFAULT 0;
DECLARE count4 INT DEFAULT 0;
DECLARE count5 INT DEFAULT 0;
DECLARE count6 INT DEFAULT 0;
DECLARE count7 INT DEFAULT 0;
DECLARE count8 INT DEFAULT 0;
DECLARE count9 INT DEFAULT 0;
DECLARE count10 INT DEFAULT 0;
DECLARE count11 INT DEFAULT 0;
DECLARE count13 INT DEFAULT 0;
DECLARE QTD141 INT DEFAULT 0;
DECLARE QTD21 INT DEFAULT 0;
DECLARE QTD151 INT DEFAULT 0;
DECLARE QTD51 INT DEFAULT 0;
DECLARE QTD142 INT DEFAULT 0;
DECLARE QTD22 INT DEFAULT 0;
DECLARE QTD152 INT DEFAULT 0;
DECLARE QTD52 INT DEFAULT 0;
DECLARE QTD143 INT DEFAULT 0;
DECLARE QTD23 INT DEFAULT 0;
DECLARE QTD153 INT DEFAULT 0;
DECLARE QTD53 INT DEFAULT 0;
DECLARE QTD144 INT DEFAULT 0;
DECLARE QTD24 INT DEFAULT 0;
DECLARE QTD154 INT DEFAULT 0;
DECLARE QTD54 INT DEFAULT 0;
DECLARE QTD145 INT DEFAULT 0;
DECLARE QTD25 INT DEFAULT 0;
DECLARE QTD155 INT DEFAULT 0;
DECLARE QTD55 INT DEFAULT 0;
DECLARE QTD146 INT DEFAULT 0;
DECLARE QTD26 INT DEFAULT 0;
DECLARE QTD156 INT DEFAULT 0;
DECLARE QTD56 INT DEFAULT 0;
DECLARE QTD147 INT DEFAULT 0;
DECLARE QTD27 INT DEFAULT 0;
DECLARE QTD157 INT DEFAULT 0;
DECLARE QTD57 INT DEFAULT 0;
DECLARE QTD148 INT DEFAULT 0;
DECLARE QTD28 INT DEFAULT 0;
DECLARE QTD158 INT DEFAULT 0;
DECLARE QTD58 INT DEFAULT 0;
DECLARE QTD149 INT DEFAULT 0;
DECLARE QTD29 INT DEFAULT 0;
DECLARE QTD159 INT DEFAULT 0;
DECLARE QTD59 INT DEFAULT 0;
DECLARE QTD1410 INT DEFAULT 0;
DECLARE QTD210 INT DEFAULT 0;
DECLARE QTD1510 INT DEFAULT 0;
DECLARE QTD510 INT DEFAULT 0;
DECLARE QTD1411 INT DEFAULT 0;
DECLARE QTD211 INT DEFAULT 0;
DECLARE QTD1511 INT DEFAULT 0;
DECLARE QTD511 INT DEFAULT 0;
DECLARE QTD1413 INT DEFAULT 0;
DECLARE QTD213 INT DEFAULT 0;
DECLARE QTD1513 INT DEFAULT 0;
DECLARE QTD513 INT DEFAULT 0;
DECLARE id_prot varchar(45);
DECLARE ID_CART varchar(90);
declare v_DT_PROTOCOLADO date;
declare v_DT_DISTRIBUIDO date;
declare v_DT_ANALISE date; 
declare v_DT_DEFERIDO date; 
declare v_DT_PRONTO date;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

DROP TEMPORARY TABLE IF EXISTS BASE_PROT2;


CREATE TEMPORARY TABLE IF NOT EXISTS BASE_PROT2 AS (select cd_protocolo_processo, dt_abertura_processo, s.ds_servico as SERV, i.sg_tipo_interessado as TIPO  from processo_andamento pa, processo p, processo_servico ps, interessado i, servico s where pa.id_processo = p.id_processo and p.id_interessado = i.id_interessado and i.sg_tipo_interessado = if(TIPO_CLI=0,i.sg_tipo_interessado,TIPO_CLI)  and p.id_processo = ps.id_processo and s.id_carteira = CARTEIRA and s.id_servico =  if(SERVICO=0,s.id_servico,SERVICO) and id_unidade = UNIDADE and  s.id_servico = ps.id_servico and dt_processo_andamento between INICIO and FIM and id_processo_status = 8);



BEGIN
DECLARE c_protocolo1 CURSOR FOR SELECT cd_protocolo_processo, SERV
FROM
    BASE_PROT2 order by dt_abertura_processo asc; 
  

open c_protocolo1;

read_loop: LOOP

FETCH  c_protocolo1 into id_prot, ID_CART;


IF (/*count1 = AMOSTRAGEM OR*/ done = 1) THEN
          LEAVE read_loop;
         ELSE
          
          select Count(*) into QTD141 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 14;
               
               IF QTD141 > 0 THEN
			
				select Count(*) into QTD21 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 2;
            
                 IF QTD21 = 1 THEN      
                  
                  
                  select Count(*) into QTD151 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 15;
                  
                  select Count(*) into QTD51 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 5;
                  
							IF (QTD151 = 0 and QTD51=0) THEN 
                  
                  
							/*1 - Protocolado*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_PROTOCOLADO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 1
LIMIT 1;

/*14 - Distribuido*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_DISTRIBUIDO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 14
LIMIT 1;



/*6 - DEFERIDO*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_DEFERIDO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 6
LIMIT 1;

/*8 - PRONTO*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_PRONTO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 8
LIMIT 1;

insert into media_tempo_servico values (ID_CART, id_prot, v_DT_PROTOCOLADO, v_DT_DISTRIBUIDO, DATEDIFF(v_DT_DISTRIBUIDO,v_DT_PROTOCOLADO), v_DT_DISTRIBUIDO, v_DT_DEFERIDO, DATEDIFF(v_DT_DEFERIDO, v_DT_DISTRIBUIDO), v_DT_DEFERIDO, v_DT_PRONTO, DATEDIFF(v_DT_PRONTO,v_DT_DEFERIDO), (DATEDIFF(v_DT_DISTRIBUIDO,v_DT_PROTOCOLADO) + DATEDIFF(v_DT_DEFERIDO,v_DT_DISTRIBUIDO) +  DATEDIFF(v_DT_PRONTO,v_DT_DEFERIDO)));
SELECT count1 + 1 INTO count1;              
                  
                  
							END IF;
                  
                  END IF;      
            
				END IF;
          
        END IF;



END LOOP;

set done = 0;


END;




end$$

CREATE DEFINER=`sistemas_sfpc`@`%` PROCEDURE `relatorio_v2`(in INICIO date, in FIM date, in UNIDADE varchar(3), in AMOSTRAGEM varchar(5))
BEGIN

DECLARE done INT DEFAULT 0;
DECLARE count1 INT DEFAULT 0;
DECLARE count2 INT DEFAULT 0;
DECLARE count3 INT DEFAULT 0;
DECLARE count4 INT DEFAULT 0;
DECLARE count5 INT DEFAULT 0;
DECLARE count6 INT DEFAULT 0;
DECLARE count7 INT DEFAULT 0;
DECLARE count8 INT DEFAULT 0;
DECLARE count9 INT DEFAULT 0;
DECLARE count10 INT DEFAULT 0;
DECLARE count11 INT DEFAULT 0;
DECLARE count13 INT DEFAULT 0;
DECLARE QTD141 INT DEFAULT 0;
DECLARE QTD21 INT DEFAULT 0;
DECLARE QTD151 INT DEFAULT 0;
DECLARE QTD51 INT DEFAULT 0;
DECLARE QTD142 INT DEFAULT 0;
DECLARE QTD22 INT DEFAULT 0;
DECLARE QTD152 INT DEFAULT 0;
DECLARE QTD52 INT DEFAULT 0;
DECLARE QTD143 INT DEFAULT 0;
DECLARE QTD23 INT DEFAULT 0;
DECLARE QTD153 INT DEFAULT 0;
DECLARE QTD53 INT DEFAULT 0;
DECLARE QTD144 INT DEFAULT 0;
DECLARE QTD24 INT DEFAULT 0;
DECLARE QTD154 INT DEFAULT 0;
DECLARE QTD54 INT DEFAULT 0;
DECLARE QTD145 INT DEFAULT 0;
DECLARE QTD25 INT DEFAULT 0;
DECLARE QTD155 INT DEFAULT 0;
DECLARE QTD55 INT DEFAULT 0;
DECLARE QTD146 INT DEFAULT 0;
DECLARE QTD26 INT DEFAULT 0;
DECLARE QTD156 INT DEFAULT 0;
DECLARE QTD56 INT DEFAULT 0;
DECLARE QTD147 INT DEFAULT 0;
DECLARE QTD27 INT DEFAULT 0;
DECLARE QTD157 INT DEFAULT 0;
DECLARE QTD57 INT DEFAULT 0;
DECLARE QTD148 INT DEFAULT 0;
DECLARE QTD28 INT DEFAULT 0;
DECLARE QTD158 INT DEFAULT 0;
DECLARE QTD58 INT DEFAULT 0;
DECLARE QTD149 INT DEFAULT 0;
DECLARE QTD29 INT DEFAULT 0;
DECLARE QTD159 INT DEFAULT 0;
DECLARE QTD59 INT DEFAULT 0;
DECLARE QTD1410 INT DEFAULT 0;
DECLARE QTD210 INT DEFAULT 0;
DECLARE QTD1510 INT DEFAULT 0;
DECLARE QTD510 INT DEFAULT 0;
DECLARE QTD1411 INT DEFAULT 0;
DECLARE QTD211 INT DEFAULT 0;
DECLARE QTD1511 INT DEFAULT 0;
DECLARE QTD511 INT DEFAULT 0;
DECLARE QTD1413 INT DEFAULT 0;
DECLARE QTD213 INT DEFAULT 0;
DECLARE QTD1513 INT DEFAULT 0;
DECLARE QTD513 INT DEFAULT 0;
DECLARE id_prot varchar(45);
DECLARE ID_CART varchar(45);
declare v_DT_PROTOCOLADO date;
declare v_DT_DISTRIBUIDO date;
declare v_DT_ANALISE date; 
declare v_DT_DEFERIDO date; 
declare v_DT_PRONTO date;

truncate table REPORT_PROCESSO;
DROP TEMPORARY TABLE IF EXISTS BASE_PROT1;
DROP TEMPORARY TABLE IF EXISTS BASE_PROT2;
DROP TEMPORARY TABLE IF EXISTS BASE_PROT3;
DROP TEMPORARY TABLE IF EXISTS BASE_PROT4;
DROP TEMPORARY TABLE IF EXISTS BASE_PROT5;
DROP TEMPORARY TABLE IF EXISTS BASE_PROT6;
DROP TEMPORARY TABLE IF EXISTS BASE_PROT7;
DROP TEMPORARY TABLE IF EXISTS BASE_PROT8;
DROP TEMPORARY TABLE IF EXISTS BASE_PROT9;
DROP TEMPORARY TABLE IF EXISTS BASE_PROT10;
DROP TEMPORARY TABLE IF EXISTS BASE_PROT11;
DROP TEMPORARY TABLE IF EXISTS BASE_PROT13;

/*CR CAC*/
CREATE TEMPORARY TABLE IF NOT EXISTS BASE_PROT1 AS (select cd_protocolo_processo, dt_abertura_processo, 'CR CAC' as SERVICO from processo_andamento pa, processo p, processo_servico ps, interessado i where pa.id_processo = p.id_processo and p.id_interessado = i.id_interessado and i.sg_tipo_interessado = 'PF' and p.id_processo = ps.id_processo and id_carteira = 1 and id_servico = 14 and id_unidade = UNIDADE and dt_processo_andamento between INICIO and FIM and id_processo_status = 8);
/*CR PJ*/
CREATE TEMPORARY TABLE IF NOT EXISTS BASE_PROT2 AS (select cd_protocolo_processo, dt_abertura_processo, 'CR PJ' as SERVICO from processo_andamento pa, processo p, processo_servico ps, interessado i where pa.id_processo = p.id_processo and p.id_interessado = i.id_interessado and i.sg_tipo_interessado = 'PJ' and p.id_processo = ps.id_processo and id_carteira = 1 and id_servico = 14 and id_unidade = UNIDADE and dt_processo_andamento between INICIO and FIM and id_processo_status = 8);
/*CR UTLZ BLIND*/
CREATE TEMPORARY TABLE IF NOT EXISTS BASE_PROT3 AS (select cd_protocolo_processo, dt_abertura_processo, 'CR UTLZ BLIND' as SERVICO from processo_andamento pa, processo p, processo_servico ps where pa.id_processo = p.id_processo and p.id_processo = ps.id_processo and id_carteira = 11 and id_servico = 90 and id_unidade = UNIDADE and dt_processo_andamento between INICIO and FIM and id_processo_status = 8);
/*MUDANÇA DE ACERVO*/
CREATE TEMPORARY TABLE IF NOT EXISTS BASE_PROT4 AS (select cd_protocolo_processo, dt_abertura_processo, 'MUDANÇA DE ACERVO' as SERVICO from processo_andamento pa, processo p, processo_servico ps where pa.id_processo = p.id_processo and p.id_processo = ps.id_processo and id_carteira = 2 and id_servico = 25 and id_unidade = UNIDADE and dt_processo_andamento between INICIO and FIM and id_processo_status = 8);
/*TRANSF SINARM P/ SIGMA*/
CREATE TEMPORARY TABLE IF NOT EXISTS BASE_PROT5 AS (select cd_protocolo_processo, dt_abertura_processo, 'TRANSF SINARM P/ SIGMA' as SERVICO from processo_andamento pa, processo p, processo_servico ps where pa.id_processo = p.id_processo and p.id_processo = ps.id_processo and id_carteira = 2 and id_servico = 39 and id_unidade = UNIDADE and dt_processo_andamento between INICIO and FIM and id_processo_status = 8);
/*TRANSF ENTRE CAC AMB 2 RM*/
CREATE TEMPORARY TABLE IF NOT EXISTS BASE_PROT6 AS (select cd_protocolo_processo, dt_abertura_processo, 'TRANSF ENTRE CAC AMB 2 RM' as SERVICO from processo_andamento pa, processo p, processo_servico ps where pa.id_processo = p.id_processo and p.id_processo = ps.id_processo and id_carteira = 2 and id_servico = 40 and id_unidade = UNIDADE and dt_processo_andamento between INICIO and FIM and id_processo_status = 8);



BEGIN
DECLARE c_protocolo1 CURSOR FOR SELECT cd_protocolo_processo, SERVICO
FROM
    BASE_PROT1 order by dt_abertura_processo desc; 
    
    
DECLARE c_protocolo2 CURSOR FOR SELECT cd_protocolo_processo, SERVICO
FROM
    BASE_PROT2 order by dt_abertura_processo desc;


DECLARE c_protocolo3 CURSOR FOR SELECT cd_protocolo_processo, SERVICO
FROM
    BASE_PROT3 order by dt_abertura_processo desc;

DECLARE c_protocolo4 CURSOR FOR SELECT cd_protocolo_processo, SERVICO
FROM
    BASE_PROT4 order by dt_abertura_processo desc;

DECLARE c_protocolo5 CURSOR FOR SELECT cd_protocolo_processo, SERVICO
FROM
    BASE_PROT5 order by dt_abertura_processo desc;
    
DECLARE c_protocolo6 CURSOR FOR SELECT cd_protocolo_processo, SERVICO
FROM
    BASE_PROT6 order by dt_abertura_processo desc;
    
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

open c_protocolo1;

read_loop: LOOP

FETCH  c_protocolo1 into id_prot, ID_CART;


IF (count1 = AMOSTRAGEM OR done = 1) THEN
          LEAVE read_loop;
         ELSE
          
          select Count(*) into QTD141 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 14;
               
               IF QTD141 > 0 THEN
			
				select Count(*) into QTD21 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 2;
            
                 IF QTD21 = 1 THEN      
                  
                  
                  select Count(*) into QTD151 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 15;
                  
                  select Count(*) into QTD51 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 5;
                  
							IF (QTD151 = 0 and QTD51=0) THEN 
                  
                  
							/*1 - Protocolado*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_PROTOCOLADO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 1
LIMIT 1;

/*14 - Distribuido*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_DISTRIBUIDO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 14
LIMIT 1;



/*6 - DEFERIDO*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_DEFERIDO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 6
LIMIT 1;

/*8 - PRONTO*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_PRONTO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 8
LIMIT 1;

insert into REPORT_PROCESSO values (ID_CART, id_prot, v_DT_PROTOCOLADO, v_DT_DISTRIBUIDO, DATEDIFF(v_DT_DISTRIBUIDO,v_DT_PROTOCOLADO), v_DT_DISTRIBUIDO, v_DT_DEFERIDO, DATEDIFF(v_DT_DEFERIDO, v_DT_DISTRIBUIDO), v_DT_DEFERIDO, v_DT_PRONTO, DATEDIFF(v_DT_PRONTO,v_DT_DEFERIDO), (DATEDIFF(v_DT_DISTRIBUIDO,v_DT_PROTOCOLADO) + DATEDIFF(v_DT_DEFERIDO,v_DT_DISTRIBUIDO) +  DATEDIFF(v_DT_PRONTO,v_DT_DEFERIDO)));
SELECT count1 + 1 INTO count1;              
                  
                  
							END IF;
                  
                  END IF;      
            
				END IF;
          
        END IF;



END LOOP;

set done = 0;

open c_protocolo2;

read_loop: LOOP

FETCH  c_protocolo2 into id_prot, ID_CART;

IF (count2 = AMOSTRAGEM OR done = 1) THEN
          LEAVE read_loop;
         ELSE
          
          select Count(*) into QTD142 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 14;
               
               IF QTD142 > 0 THEN
			
				select Count(*) into QTD22 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 2;
            
                 IF QTD22 = 1 THEN      
                  
                  
                  select Count(*) into QTD152 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 15;
                  
                  select Count(*) into QTD52 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 5;
                  
							IF (QTD152 = 0 and QTD52=0) THEN 
                  
                  
							/*1 - Protocolado*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_PROTOCOLADO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 1
LIMIT 1;

/*14 - Distribuido*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_DISTRIBUIDO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 14
LIMIT 1;


/*6 - DEFERIDO*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_DEFERIDO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 6
LIMIT 1;

/*8 - PRONTO*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_PRONTO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 8
LIMIT 1;


insert into REPORT_PROCESSO values (ID_CART, id_prot, v_DT_PROTOCOLADO, v_DT_DISTRIBUIDO, DATEDIFF(v_DT_DISTRIBUIDO,v_DT_PROTOCOLADO), v_DT_DISTRIBUIDO, v_DT_DEFERIDO, DATEDIFF(v_DT_DEFERIDO, v_DT_DISTRIBUIDO), v_DT_DEFERIDO, v_DT_PRONTO, DATEDIFF(v_DT_PRONTO,v_DT_DEFERIDO), (DATEDIFF(v_DT_DISTRIBUIDO,v_DT_PROTOCOLADO) + DATEDIFF(v_DT_DEFERIDO,v_DT_DISTRIBUIDO) +  DATEDIFF(v_DT_PRONTO,v_DT_DEFERIDO)));

SELECT count2 + 1 INTO count2;              
                  
                  
							END IF;
                  
                  END IF;      
            
				END IF;
          
        END IF;



END LOOP;

set done = 0;

open c_protocolo3;

read_loop: LOOP

FETCH  c_protocolo3 into id_prot, ID_CART;

IF (count3 = AMOSTRAGEM OR done = 1) THEN
          LEAVE read_loop;
         ELSE
          
          select Count(*) into QTD143 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 14;
               
               IF QTD143 > 0 THEN
			
				select Count(*) into QTD23 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 2;
            
                 IF QTD23 = 1 THEN      
                  
                  
                  select Count(*) into QTD153 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 15;
                  
                  select Count(*) into QTD53 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 5;
                  
							IF (QTD153 = 0 and QTD53=0) THEN 
                  
                  
							/*1 - Protocolado*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_PROTOCOLADO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 1
LIMIT 1;

/*14 - Distribuido*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_DISTRIBUIDO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 14
LIMIT 1;


/*6 - DEFERIDO*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_DEFERIDO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 6
LIMIT 1;

/*8 - PRONTO*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_PRONTO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 8
LIMIT 1;


insert into REPORT_PROCESSO values (ID_CART, id_prot, v_DT_PROTOCOLADO, v_DT_DISTRIBUIDO, DATEDIFF(v_DT_DISTRIBUIDO,v_DT_PROTOCOLADO), v_DT_DISTRIBUIDO, v_DT_DEFERIDO, DATEDIFF(v_DT_DEFERIDO, v_DT_DISTRIBUIDO), v_DT_DEFERIDO, v_DT_PRONTO, DATEDIFF(v_DT_PRONTO,v_DT_DEFERIDO), (DATEDIFF(v_DT_DISTRIBUIDO,v_DT_PROTOCOLADO) + DATEDIFF(v_DT_DEFERIDO,v_DT_DISTRIBUIDO)  + DATEDIFF(v_DT_PRONTO,v_DT_DEFERIDO)));
SELECT count3 + 1 INTO count3;              
                  
                  
							END IF;
                  
                  END IF;      
            
				END IF;
          
        END IF;



END LOOP;

set done = 0;

open c_protocolo4;

read_loop: LOOP

FETCH  c_protocolo4 into id_prot, ID_CART;

IF (count4 = AMOSTRAGEM OR done = 1) THEN
          LEAVE read_loop;
         ELSE
          
          select Count(*) into QTD144 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 14;
               
               IF QTD144 > 0 THEN
			
				select Count(*) into QTD24 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 2;
            
                 IF QTD24 = 1 THEN      
                  
                  
                  select Count(*) into QTD154 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 15;
                  
                  select Count(*) into QTD54 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 5;
                  
							IF (QTD154 = 0 and QTD54=0) THEN 
                  
                  
							/*1 - Protocolado*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_PROTOCOLADO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 1
LIMIT 1;

/*14 - Distribuido*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_DISTRIBUIDO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 14
LIMIT 1;


/*6 - DEFERIDO*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_DEFERIDO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 6
LIMIT 1;

/*8 - PRONTO*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_PRONTO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 8
LIMIT 1;


insert into REPORT_PROCESSO values (ID_CART, id_prot, v_DT_PROTOCOLADO, v_DT_DISTRIBUIDO, DATEDIFF(v_DT_DISTRIBUIDO,v_DT_PROTOCOLADO), v_DT_DISTRIBUIDO, v_DT_DEFERIDO, DATEDIFF(v_DT_DEFERIDO, v_DT_DISTRIBUIDO), v_DT_DEFERIDO, v_DT_PRONTO, DATEDIFF(v_DT_PRONTO,v_DT_DEFERIDO), (DATEDIFF(v_DT_DISTRIBUIDO,v_DT_PROTOCOLADO) + DATEDIFF(v_DT_DEFERIDO,v_DT_DISTRIBUIDO)  + DATEDIFF(v_DT_PRONTO,v_DT_DEFERIDO)));
SELECT count4 + 1 INTO count4;              
                  
                  
							END IF;
                  
                  END IF;      
            
				END IF;
          
        END IF;



END LOOP;

set done = 0;

open c_protocolo5;

read_loop: LOOP

FETCH  c_protocolo5 into id_prot, ID_CART;

IF (count5 = AMOSTRAGEM OR done = 1) THEN
          LEAVE read_loop;
         ELSE
          
          select Count(*) into QTD145 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 14;
               
               IF QTD145 > 0 THEN
			
				select Count(*) into QTD25 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 2;
            
                 IF QTD25 = 1 THEN      
                  
                  
                  select Count(*) into QTD155 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 15;
                  
                  select Count(*) into QTD55 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 5;
                  
							IF (QTD155 = 0 and QTD55=0) THEN 
                  
                  
							/*1 - Protocolado*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_PROTOCOLADO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 1
LIMIT 1;

/*14 - Distribuido*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_DISTRIBUIDO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 14
LIMIT 1;


/*6 - DEFERIDO*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_DEFERIDO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 6
LIMIT 1;

/*8 - PRONTO*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_PRONTO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 8
LIMIT 1;


insert into REPORT_PROCESSO values (ID_CART, id_prot, v_DT_PROTOCOLADO, v_DT_DISTRIBUIDO, DATEDIFF(v_DT_DISTRIBUIDO,v_DT_PROTOCOLADO), v_DT_DISTRIBUIDO, v_DT_DEFERIDO, DATEDIFF(v_DT_DEFERIDO, v_DT_DISTRIBUIDO), v_DT_DEFERIDO, v_DT_PRONTO, DATEDIFF(v_DT_PRONTO,v_DT_DEFERIDO), (DATEDIFF(v_DT_DISTRIBUIDO,v_DT_PROTOCOLADO) + DATEDIFF(v_DT_DEFERIDO,v_DT_DISTRIBUIDO)  + DATEDIFF(v_DT_PRONTO,v_DT_DEFERIDO)));
SELECT count5 + 1 INTO count5;              
                  
                  
							END IF;
                  
                  END IF;      
            
				END IF;
          
        END IF;



END LOOP;

set done = 0;

open c_protocolo6;

read_loop: LOOP

FETCH  c_protocolo6 into id_prot, ID_CART;

IF (count6 = AMOSTRAGEM OR done = 1) THEN
          LEAVE read_loop;
         ELSE
          
          select Count(*) into QTD146 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 14;
               
               IF QTD146 > 0 THEN
			
				select Count(*) into QTD26 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 2;
            
                 IF QTD26 = 1 THEN      
                  
                  
                  select Count(*) into QTD156 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 15;
                  
                  select Count(*) into QTD56 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 5;
                  
							IF (QTD156 = 0 and QTD56=0) THEN 
                  
                  
							/*1 - Protocolado*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_PROTOCOLADO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 1
LIMIT 1;

/*14 - Distribuido*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_DISTRIBUIDO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 14
LIMIT 1;


/*6 - DEFERIDO*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_DEFERIDO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 6
LIMIT 1;

/*8 - PRONTO*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_PRONTO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 8
LIMIT 1;


insert into REPORT_PROCESSO values (ID_CART, id_prot, v_DT_PROTOCOLADO, v_DT_DISTRIBUIDO, DATEDIFF(v_DT_DISTRIBUIDO,v_DT_PROTOCOLADO), v_DT_DISTRIBUIDO, v_DT_DEFERIDO, DATEDIFF(v_DT_DEFERIDO, v_DT_DISTRIBUIDO), v_DT_DEFERIDO, v_DT_PRONTO, DATEDIFF(v_DT_PRONTO,v_DT_DEFERIDO), (DATEDIFF(v_DT_DISTRIBUIDO,v_DT_PROTOCOLADO) + DATEDIFF(v_DT_DEFERIDO,v_DT_DISTRIBUIDO)  + DATEDIFF(v_DT_PRONTO,v_DT_DEFERIDO)));
SELECT count6 + 1 INTO count6;              
                  
                  
							END IF;
                  
                  END IF;      
            
				END IF;
          
        END IF;



END LOOP;



END;




end$$

CREATE DEFINER=`sistemas_sfpc`@`%` PROCEDURE `relatorio_v4`(in INICIO date, in FIM date, in UNIDADE varchar(3), in AMOSTRAGEM varchar(5), CARTEIRA int(3), SERVICO int(3), TIPO_CLI varchar(5))
BEGIN


DECLARE done INT DEFAULT 0;
DECLARE count1 INT DEFAULT 0;
DECLARE count2 INT DEFAULT 0;
DECLARE count3 INT DEFAULT 0;
DECLARE count4 INT DEFAULT 0;
DECLARE count5 INT DEFAULT 0;
DECLARE count6 INT DEFAULT 0;
DECLARE count7 INT DEFAULT 0;
DECLARE count8 INT DEFAULT 0;
DECLARE count9 INT DEFAULT 0;
DECLARE count10 INT DEFAULT 0;
DECLARE count11 INT DEFAULT 0;
DECLARE count13 INT DEFAULT 0;
DECLARE QTD141 INT DEFAULT 0;
DECLARE QTD21 INT DEFAULT 0;
DECLARE QTD151 INT DEFAULT 0;
DECLARE QTD51 INT DEFAULT 0;
DECLARE QTD142 INT DEFAULT 0;
DECLARE QTD22 INT DEFAULT 0;
DECLARE QTD152 INT DEFAULT 0;
DECLARE QTD52 INT DEFAULT 0;
DECLARE QTD143 INT DEFAULT 0;
DECLARE QTD23 INT DEFAULT 0;
DECLARE QTD153 INT DEFAULT 0;
DECLARE QTD53 INT DEFAULT 0;
DECLARE QTD144 INT DEFAULT 0;
DECLARE QTD24 INT DEFAULT 0;
DECLARE QTD154 INT DEFAULT 0;
DECLARE QTD54 INT DEFAULT 0;
DECLARE QTD145 INT DEFAULT 0;
DECLARE QTD25 INT DEFAULT 0;
DECLARE QTD155 INT DEFAULT 0;
DECLARE QTD55 INT DEFAULT 0;
DECLARE QTD146 INT DEFAULT 0;
DECLARE QTD26 INT DEFAULT 0;
DECLARE QTD156 INT DEFAULT 0;
DECLARE QTD56 INT DEFAULT 0;
DECLARE QTD147 INT DEFAULT 0;
DECLARE QTD27 INT DEFAULT 0;
DECLARE QTD157 INT DEFAULT 0;
DECLARE QTD57 INT DEFAULT 0;
DECLARE QTD148 INT DEFAULT 0;
DECLARE QTD28 INT DEFAULT 0;
DECLARE QTD158 INT DEFAULT 0;
DECLARE QTD58 INT DEFAULT 0;
DECLARE QTD149 INT DEFAULT 0;
DECLARE QTD29 INT DEFAULT 0;
DECLARE QTD159 INT DEFAULT 0;
DECLARE QTD59 INT DEFAULT 0;
DECLARE QTD1410 INT DEFAULT 0;
DECLARE QTD210 INT DEFAULT 0;
DECLARE QTD1510 INT DEFAULT 0;
DECLARE QTD510 INT DEFAULT 0;
DECLARE QTD1411 INT DEFAULT 0;
DECLARE QTD211 INT DEFAULT 0;
DECLARE QTD1511 INT DEFAULT 0;
DECLARE QTD511 INT DEFAULT 0;
DECLARE QTD1413 INT DEFAULT 0;
DECLARE QTD213 INT DEFAULT 0;
DECLARE QTD1513 INT DEFAULT 0;
DECLARE QTD513 INT DEFAULT 0;
DECLARE id_prot varchar(45);
DECLARE ID_CART varchar(90);
declare v_DT_PROTOCOLADO date;
declare v_DT_DISTRIBUIDO date;
declare v_DT_ANALISE date; 
declare v_DT_DEFERIDO date; 
declare v_DT_PRONTO date;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

truncate table REPORT_PROCESSO;
DROP TEMPORARY TABLE IF EXISTS BASE_PROT1;


CREATE TEMPORARY TABLE IF NOT EXISTS BASE_PROT1 AS (select cd_protocolo_processo, dt_abertura_processo, s.ds_servico as SERV, i.sg_tipo_interessado as TIPO  from processo_andamento pa, processo p, processo_servico ps, interessado i, servico s where pa.id_processo = p.id_processo and p.id_interessado = i.id_interessado and i.sg_tipo_interessado = if(TIPO_CLI=0,i.sg_tipo_interessado,TIPO_CLI)  and p.id_processo = ps.id_processo and s.id_carteira = CARTEIRA and s.id_servico =  if(SERVICO=0,s.id_servico,SERVICO) and id_unidade = UNIDADE and  s.id_servico = ps.id_servico and dt_processo_andamento between INICIO and FIM and id_processo_status = 8);



BEGIN
DECLARE c_protocolo1 CURSOR FOR SELECT cd_protocolo_processo, SERV
FROM
    BASE_PROT1 order by dt_abertura_processo asc; 
  

open c_protocolo1;

read_loop: LOOP

FETCH  c_protocolo1 into id_prot, ID_CART;


IF (/*count1 = AMOSTRAGEM OR*/ done = 1) THEN
          LEAVE read_loop;
         ELSE
          
          select Count(*) into QTD141 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 14;
               
               IF QTD141 > 0 THEN
			
				select Count(*) into QTD21 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 2;
            
                 IF QTD21 = 1 THEN      
                  
                  
                  select Count(*) into QTD151 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 15;
                  
                  select Count(*) into QTD51 from processo_andamento pa, processo p where pa.id_processo = p.id_processo and id_unidade = UNIDADE and cd_protocolo_processo = id_prot and id_processo_status = 5;
                  
							IF (QTD151 = 0 and QTD51=0) THEN 
                  
                  
							/*1 - Protocolado*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_PROTOCOLADO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 1
LIMIT 1;

/*14 - Distribuido*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_DISTRIBUIDO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 14
LIMIT 1;



/*6 - DEFERIDO*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_DEFERIDO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 6
LIMIT 1;

/*8 - PRONTO*/
SELECT 
    pa.dt_processo_andamento
INTO v_DT_PRONTO FROM
    processo_andamento pa,
    processo p
WHERE
    pa.id_processo = p.id_processo
        AND cd_protocolo_processo = id_prot
        AND id_processo_status = 8
LIMIT 1;

insert into REPORT_PROCESSO values (ID_CART, id_prot, v_DT_PROTOCOLADO, v_DT_DISTRIBUIDO, DATEDIFF(v_DT_DISTRIBUIDO,v_DT_PROTOCOLADO), v_DT_DISTRIBUIDO, v_DT_DEFERIDO, DATEDIFF(v_DT_DEFERIDO, v_DT_DISTRIBUIDO), v_DT_DEFERIDO, v_DT_PRONTO, DATEDIFF(v_DT_PRONTO,v_DT_DEFERIDO), (DATEDIFF(v_DT_DISTRIBUIDO,v_DT_PROTOCOLADO) + DATEDIFF(v_DT_DEFERIDO,v_DT_DISTRIBUIDO) +  DATEDIFF(v_DT_PRONTO,v_DT_DEFERIDO)));
SELECT count1 + 1 INTO count1;              
                  
                  
							END IF;
                  
                  END IF;      
            
				END IF;
          
        END IF;



END LOOP;

set done = 0;


END;




end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt`
--

CREATE TABLE IF NOT EXISTS `adt` (
`id_adt` int(11) NOT NULL,
  `id_adt_status` int(11) NOT NULL,
  `dt_adt` datetime DEFAULT NULL,
  `nr_adt` varchar(32) NOT NULL,
  `nr_bar` varchar(32) NOT NULL,
  `st_publicado` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_acervo`
--

CREATE TABLE IF NOT EXISTS `adt_acervo` (
`id_adt_acervo` int(11) NOT NULL,
  `nm_adt_acervo` varchar(150) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_arma`
--

CREATE TABLE IF NOT EXISTS `adt_arma` (
`id_adt_arma` int(11) NOT NULL,
  `id_adt_arma_modelo` int(11) NOT NULL,
  `id_adt_arma_pais_origem` int(11) NOT NULL,
  `id_adt_arma_acabamento` int(11) NOT NULL,
  `nr_arma` varchar(45) DEFAULT NULL,
  `nr_sigma` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=746 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_arma_acabamento`
--

CREATE TABLE IF NOT EXISTS `adt_arma_acabamento` (
`id_adt_arma_acabamento` int(11) NOT NULL,
  `nm_adt_arma_acabamento` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_arma_alma`
--

CREATE TABLE IF NOT EXISTS `adt_arma_alma` (
`id_adt_arma_alma` int(11) NOT NULL,
  `nm_adt_arma_alma` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_arma_calibre`
--

CREATE TABLE IF NOT EXISTS `adt_arma_calibre` (
`id_adt_arma_calibre` int(11) NOT NULL,
  `nm_adt_arma_calibre` varchar(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_arma_especie`
--

CREATE TABLE IF NOT EXISTS `adt_arma_especie` (
`id_adt_arma_especie` int(11) NOT NULL,
  `nm_adt_arma_especie` varchar(45) DEFAULT NULL,
  `sg_adt_arma_especie` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_arma_fornecedor`
--

CREATE TABLE IF NOT EXISTS `adt_arma_fornecedor` (
`id_adt_arma_fornecedor` int(11) NOT NULL,
  `nm_adt_arma_fornecedor` varchar(200) DEFAULT NULL,
  `cnpj` varchar(14) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_arma_funcionamento`
--

CREATE TABLE IF NOT EXISTS `adt_arma_funcionamento` (
`id_adt_arma_funcionamento` int(11) NOT NULL,
  `nm_adt_arma_funcionamento` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_arma_marca`
--

CREATE TABLE IF NOT EXISTS `adt_arma_marca` (
`id_adt_arma_marca` int(11) NOT NULL,
  `nm_adt_arma_marca` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_arma_modelo`
--

CREATE TABLE IF NOT EXISTS `adt_arma_modelo` (
`id_adt_arma_modelo` int(11) NOT NULL,
  `id_adt_arma_marca` int(11) NOT NULL,
  `id_adt_arma_especie` int(11) NOT NULL,
  `id_adt_arma_calibre` int(11) NOT NULL,
  `id_adt_arma_funcionamento` int(11) NOT NULL,
  `id_adt_arma_alma` int(11) NOT NULL,
  `nm_arma_modelo` varchar(45) NOT NULL,
  `qtd_cano` int(11) DEFAULT NULL,
  `nr_raias` int(11) DEFAULT NULL,
  `sentido_raia` varchar(10) DEFAULT NULL,
  `comprimento_cano` varchar(45) DEFAULT NULL,
  `qtd_carregamento` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_arma_pais_origem`
--

CREATE TABLE IF NOT EXISTS `adt_arma_pais_origem` (
`id_adt_arma_pais_origem` int(11) NOT NULL,
  `nm_adt_arma_pais_origem` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_assinaturas`
--

CREATE TABLE IF NOT EXISTS `adt_assinaturas` (
  `nm_adt_assinaturas` varchar(128) NOT NULL,
  `pg_adt_assinaturas` varchar(18) NOT NULL,
  `funcao_adt_assinaturas` varchar(128) NOT NULL,
  `ordem` int(1) NOT NULL,
  `id_unidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_atividade_cr`
--

CREATE TABLE IF NOT EXISTS `adt_atividade_cr` (
`id_adt_atividade_cr` int(11) NOT NULL,
  `nm_adt_atividade_cr` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_cr`
--

CREATE TABLE IF NOT EXISTS `adt_cr` (
`id_adt_cr` int(11) NOT NULL,
  `id_interessado` int(11) NOT NULL,
  `nr_cr` varchar(45) DEFAULT NULL,
  `dt_validade` datetime DEFAULT NULL,
  `id_adt_materia` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=286 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_materia`
--

CREATE TABLE IF NOT EXISTS `adt_materia` (
`id_adt_materia` int(11) NOT NULL,
  `id_adt_materia_tipo` int(11) NOT NULL,
  `id_processo` int(11) DEFAULT NULL,
  `id_processo_status` int(11) NOT NULL,
  `txt_adt_materia` varchar(2000) DEFAULT NULL,
  `dt_criacao` datetime DEFAULT NULL,
  `st_publicada` int(11) NOT NULL,
  `id_adt` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2162 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_materia_andamento`
--

CREATE TABLE IF NOT EXISTS `adt_materia_andamento` (
`id_adt_materia_andamento` int(11) NOT NULL,
  `id_adt_materia` int(11) NOT NULL,
  `id_adt_materia_status` int(11) NOT NULL,
  `id_login` int(11) NOT NULL,
  `dt_adt_materia_andamento` datetime DEFAULT NULL,
  `obs_adt_materia_andamento` varchar(2000) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3438 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_materia_arma`
--

CREATE TABLE IF NOT EXISTS `adt_materia_arma` (
  `id_adt_materia` int(11) NOT NULL,
  `id_adt_arma` int(11) NOT NULL,
  `id_adt_acervo` int(11) NOT NULL,
  `nr_nota_fiscal` varchar(45) DEFAULT NULL,
  `id_adt_arma_fornecedor` int(11) DEFAULT NULL,
  `id_interessado_cedente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_materia_cr_atividade`
--

CREATE TABLE IF NOT EXISTS `adt_materia_cr_atividade` (
  `id_adt_materia` int(11) NOT NULL,
  `id_adt_atividade_cr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_materia_status`
--

CREATE TABLE IF NOT EXISTS `adt_materia_status` (
`id_adt_materia_status` int(11) NOT NULL,
  `nm_adt_materia_status` varchar(45) DEFAULT NULL,
  `ds_adt_materia_status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_materia_tipo`
--

CREATE TABLE IF NOT EXISTS `adt_materia_tipo` (
`id_adt_materia_tipo` int(11) NOT NULL,
  `nm_adt_materia_tipo` varchar(200) DEFAULT NULL,
  `pre_texto` text,
  `pos_texto` text
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `adt_materia_vinc_rm`
--

CREATE TABLE IF NOT EXISTS `adt_materia_vinc_rm` (
  `id_adt_materia` int(11) NOT NULL,
  `rm_origem` int(2) NOT NULL,
  `rm_destino` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamento_assunto`
--

CREATE TABLE IF NOT EXISTS `agendamento_assunto` (
`id_agendamento_assunto` int(11) NOT NULL,
  `nm_agendamento_assunto` varchar(45) DEFAULT NULL,
  `ds_agendamento_assunto` varchar(400) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamento_assunto_horario`
--

CREATE TABLE IF NOT EXISTS `agendamento_assunto_horario` (
  `id_agendamento_horario` int(11) NOT NULL,
  `id_agendamento_assunto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamento_data`
--

CREATE TABLE IF NOT EXISTS `agendamento_data` (
`id_agendamento_data` int(11) NOT NULL,
  `id_login` int(11) NOT NULL,
  `unidade_id_unidade` int(11) NOT NULL,
  `dt_agendamento` datetime DEFAULT NULL,
  `st_agendamento` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7291 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamento_horario`
--

CREATE TABLE IF NOT EXISTS `agendamento_horario` (
`id_agendamento_horario` int(11) NOT NULL,
  `id_agendamento_data` int(11) NOT NULL,
  `hr_agendamento_horario` varchar(5) DEFAULT NULL,
  `qt_max_agendamento_horario` int(11) DEFAULT NULL,
  `qt_requerente_agendado` int(11) DEFAULT NULL,
  `qt_max_protocolo_horario` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=93517 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamento_horario_interessado`
--

CREATE TABLE IF NOT EXISTS `agendamento_horario_interessado` (
`id_agendamento_horario_interessado` int(11) NOT NULL,
  `id_agendamento_horario` int(11) NOT NULL,
  `id_agendamento_login` int(11) NOT NULL,
  `id_interessado` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamento_login`
--

CREATE TABLE IF NOT EXISTS `agendamento_login` (
`id_agendamento_login` int(11) NOT NULL,
  `id_cidade` int(11) NOT NULL,
  `id_agendamento_login_tipo` int(11) NOT NULL,
  `id_arquivo` int(11) NOT NULL,
  `cpf_login` varchar(45) DEFAULT NULL,
  `nm_completo` varchar(80) DEFAULT NULL,
  `nr_celular` varchar(15) DEFAULT NULL,
  `nm_senha` varchar(100) DEFAULT NULL,
  `ds_dica_senha` varchar(60) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `st_login` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19966 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamento_login_historico`
--

CREATE TABLE IF NOT EXISTS `agendamento_login_historico` (
`id_agendamento_login_historico` int(11) NOT NULL,
  `id_agendamento_login` int(11) NOT NULL,
  `id_agendamento_login_status` int(11) NOT NULL,
  `dt_agendamento_login_historico` datetime DEFAULT NULL,
  `obs` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=55895 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamento_login_status`
--

CREATE TABLE IF NOT EXISTS `agendamento_login_status` (
`id_agendamento_login_status` int(11) NOT NULL,
  `nm_status` varchar(45) DEFAULT NULL,
  `ds_status` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamento_login_tipo`
--

CREATE TABLE IF NOT EXISTS `agendamento_login_tipo` (
`id_agendamento_login_tipo` int(11) NOT NULL,
  `nm_agendamento_login_tipo` varchar(45) DEFAULT NULL,
  `ds_agendamento_login_tipo` varchar(150) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamento_requerente`
--

CREATE TABLE IF NOT EXISTS `agendamento_requerente` (
`id_agendamento_requerente` int(11) NOT NULL,
  `id_agendamento_horario` int(11) NOT NULL,
  `id_agendamento_login` int(11) NOT NULL,
  `obs` varchar(200) DEFAULT NULL,
  `id_agendamento_assunto` int(11) NOT NULL,
  `st_agendamento_requerente_agendado` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=120221 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamento_requerente_andamento`
--

CREATE TABLE IF NOT EXISTS `agendamento_requerente_andamento` (
`id_agendamento_requerente_andamento` int(11) NOT NULL,
  `id_agendamento_requerente` int(11) NOT NULL,
  `id_agendamento_status` int(11) NOT NULL,
  `dt_agendamento_requerente_andamento` datetime DEFAULT NULL,
  `obs` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=202476 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamento_status`
--

CREATE TABLE IF NOT EXISTS `agendamento_status` (
`id_agendamento_status` int(11) NOT NULL,
  `nm_status` varchar(45) DEFAULT NULL,
  `ds_status` varchar(60) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamento_tipo_usuario_horario`
--

CREATE TABLE IF NOT EXISTS `agendamento_tipo_usuario_horario` (
  `id_agendamento_horario` int(11) NOT NULL,
  `id_agendamento_login_tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendamento_unidade_cidade`
--

CREATE TABLE IF NOT EXISTS `agendamento_unidade_cidade` (
  `id_unidade` int(11) NOT NULL,
  `id_cidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `amostragem`
--

CREATE TABLE IF NOT EXISTS `amostragem` (
  `qtd` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `arquivo`
--

CREATE TABLE IF NOT EXISTS `arquivo` (
`id_arquivo` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `nm_arquivo` varchar(150) DEFAULT NULL,
  `ds_arquivo` varchar(200) DEFAULT NULL,
  `binario` mediumblob,
  `tipo` varchar(15) DEFAULT NULL,
  `tamanho` int(11) DEFAULT NULL,
  `dt_envio` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=20743 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `atendimento`
--

CREATE TABLE IF NOT EXISTS `atendimento` (
  `dia` varchar(15) DEFAULT NULL,
  `semana` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `carteira`
--

CREATE TABLE IF NOT EXISTS `carteira` (
`id_carteira` int(11) NOT NULL,
  `sg_carteira` varchar(45) DEFAULT NULL,
  `ds_carteira` varchar(100) DEFAULT NULL,
  `cor_pasta_carteira` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `carteira_antigo`
--

CREATE TABLE IF NOT EXISTS `carteira_antigo` (
  `id_carteira` int(11) NOT NULL DEFAULT '0',
  `sg_carteira` varchar(45) DEFAULT NULL,
  `ds_carteira` varchar(100) DEFAULT NULL,
  `cor_pasta_carteira` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `carteira_nova`
--

CREATE TABLE IF NOT EXISTS `carteira_nova` (
  `id_carteira` int(11) NOT NULL DEFAULT '0',
  `sg_carteira` varchar(45) DEFAULT NULL,
  `ds_carteira` varchar(100) DEFAULT NULL,
  `cor_pasta_carteira` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cidade`
--

CREATE TABLE IF NOT EXISTS `cidade` (
`id_cidade` int(11) NOT NULL,
  `uf_cidade` varchar(45) DEFAULT NULL,
  `nm_cidade` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=936 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `city`
--

CREATE TABLE IF NOT EXISTS `city` (
`ID` int(11) NOT NULL,
  `Name` char(35) NOT NULL DEFAULT '',
  `CountryCode` char(3) NOT NULL DEFAULT '',
  `District` char(20) NOT NULL DEFAULT '',
  `Population` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4080 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `Code` char(3) NOT NULL DEFAULT '',
  `Name` char(52) NOT NULL DEFAULT '',
  `Continent` enum('Asia','Europe','North America','Africa','Oceania','Antarctica','South America') NOT NULL DEFAULT 'Asia',
  `Region` char(26) NOT NULL DEFAULT '',
  `SurfaceArea` float(10,2) NOT NULL DEFAULT '0.00',
  `IndepYear` smallint(6) DEFAULT NULL,
  `Population` int(11) NOT NULL DEFAULT '0',
  `LifeExpectancy` float(3,1) DEFAULT NULL,
  `GNP` float(10,2) DEFAULT NULL,
  `GNPOld` float(10,2) DEFAULT NULL,
  `LocalName` char(45) NOT NULL DEFAULT '',
  `GovernmentForm` char(45) NOT NULL DEFAULT '',
  `HeadOfState` char(60) DEFAULT NULL,
  `Capital` int(11) DEFAULT NULL,
  `Code2` char(2) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `countrylanguage`
--

CREATE TABLE IF NOT EXISTS `countrylanguage` (
  `CountryCode` char(3) NOT NULL DEFAULT '',
  `Language` char(30) NOT NULL DEFAULT '',
  `IsOfficial` enum('T','F') NOT NULL DEFAULT 'F',
  `Percentage` float(4,1) NOT NULL DEFAULT '0.0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `craf_pendente`
--

CREATE TABLE IF NOT EXISTS `craf_pendente` (
  `cpf` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `documento`
--

CREATE TABLE IF NOT EXISTS `documento` (
`id_documento` int(11) NOT NULL,
  `id_documento_tipo` int(11) NOT NULL,
  `id_login` int(11) NOT NULL,
  `id_processo` int(11) DEFAULT NULL,
  `id_carteira` int(11) DEFAULT NULL,
  `id_servico` int(11) DEFAULT NULL,
  `cpf` varchar(15) DEFAULT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `nm_arquivo` varchar(45) DEFAULT NULL,
  `nm_extensao` varchar(15) DEFAULT NULL,
  `vl_tamanho` int(11) DEFAULT NULL,
  `path` varchar(200) DEFAULT NULL,
  `dt_upload` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=27478 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `documento_antigo`
--

CREATE TABLE IF NOT EXISTS `documento_antigo` (
  `id_documento` int(11) NOT NULL DEFAULT '0',
  `id_documento_tipo` int(11) NOT NULL,
  `id_login` int(11) NOT NULL,
  `id_processo` int(11) DEFAULT NULL,
  `id_carteira` int(11) DEFAULT NULL,
  `id_servico` int(11) DEFAULT NULL,
  `cpf` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `cnpj` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `nm_arquivo` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `nm_extensao` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `vl_tamanho` int(11) DEFAULT NULL,
  `path` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `dt_upload` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `documento_indexador`
--

CREATE TABLE IF NOT EXISTS `documento_indexador` (
`id_documento_indexador` int(11) NOT NULL,
  `id_documento_indexador_formato` int(11) NOT NULL,
  `nm_documento_indexador` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `documento_indexador_formato`
--

CREATE TABLE IF NOT EXISTS `documento_indexador_formato` (
`id_documento_indexador_formato` int(11) NOT NULL,
  `nm_documento_indexador_formato` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `documento_tipo`
--

CREATE TABLE IF NOT EXISTS `documento_tipo` (
`id_documento_tipo` int(11) NOT NULL,
  `sg_documento_tipo` varchar(45) NOT NULL,
  `nm_documento_tipo` varchar(45) NOT NULL,
  `ds_documento_tipo` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `documento_tipo_indexadores`
--

CREATE TABLE IF NOT EXISTS `documento_tipo_indexadores` (
  `id_documento_tipo` int(11) NOT NULL,
  `id_documento_indexador` int(11) NOT NULL,
  `nr_ordem` int(11) NOT NULL,
  `st_obrigatorio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `documento_valor`
--

CREATE TABLE IF NOT EXISTS `documento_valor` (
  `id_documento` int(11) NOT NULL,
  `id_documento_indexador` int(11) NOT NULL,
  `valor` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `evento`
--

CREATE TABLE IF NOT EXISTS `evento` (
`id_evento` int(11) NOT NULL,
  `id_tipo_evento` int(11) NOT NULL,
  `dt_evento` datetime DEFAULT NULL,
  `ip_conexao` varchar(45) DEFAULT NULL,
  `obs_evento` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=36579666 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Stand-in structure for view `grafico_tempo_servico`
--
CREATE TABLE IF NOT EXISTS `grafico_tempo_servico` (
`SERVICO` varchar(90)
,`PROTOCOLO` varchar(45)
,`DATA_DE_ENTRADA` date
,`DATA_DE_DISTRIBUICAO` date
,`TEMPO_DE_PROCESSAMENTO` int(11)
,`ENTRADA_EM_ANALISE` date
,`CONCLUSAO_DA_ANALISE` date
,`TEMPO_DE_ANALISE` int(11)
,`TEMPO_DE_RECEBIMENTO_E_ANALISE` bigint(12)
,`ENTRADA_EM_REGISTRO` date
,`DATA_DE_ENTREGA` date
,`TEMPO_DE_REGISTRO` int(11)
,`TEMPO_TOTAL` int(11)
);
-- --------------------------------------------------------

--
-- Estrutura da tabela `gru`
--

CREATE TABLE IF NOT EXISTS `gru` (
`id_gru` int(11) NOT NULL,
  `nr_referencia` int(11) DEFAULT NULL,
  `competencia` varchar(7) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `nr_autenticacao` varchar(16) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=37659 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `gru_processo`
--

CREATE TABLE IF NOT EXISTS `gru_processo` (
  `id_gru` int(11) NOT NULL,
  `id_processo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `gru_tentativa_fraude`
--

CREATE TABLE IF NOT EXISTS `gru_tentativa_fraude` (
  `dt_gru_tentativa_fraude` datetime NOT NULL,
  `id_gru` int(11) NOT NULL,
  `id_login` int(11) NOT NULL,
  `id_interessado` int(11) NOT NULL,
  `id_procurador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `interessado`
--

CREATE TABLE IF NOT EXISTS `interessado` (
`id_interessado` int(11) NOT NULL,
  `id_cidade` int(11) NOT NULL,
  `sg_tipo_interessado` varchar(3) NOT NULL,
  `nm_interessado` varchar(60) DEFAULT NULL,
  `cpf_interessado` varchar(15) DEFAULT NULL,
  `cnpj_interessado` varchar(20) DEFAULT NULL,
  `cr_interessado` varchar(45) DEFAULT NULL,
  `tr_interessado` varchar(45) DEFAULT NULL,
  `nr_tel_interessado` varchar(20) DEFAULT NULL,
  `email_interessado` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=96709 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `login`
--

CREATE TABLE IF NOT EXISTS `login` (
`id_login` int(11) NOT NULL,
  `id_posto_graduacao` int(11) NOT NULL,
  `id_login_perfil` int(11) NOT NULL,
  `id_unidade` int(11) NOT NULL,
  `nm_login` varchar(45) DEFAULT NULL,
  `nm_guerra` varchar(45) DEFAULT NULL,
  `nm_senha` varchar(60) DEFAULT NULL,
  `nm_completo` varchar(80) DEFAULT NULL,
  `nm_email` varchar(45) DEFAULT NULL,
  `st_ativo` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=496 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `login_carteira`
--

CREATE TABLE IF NOT EXISTS `login_carteira` (
  `id_login` int(11) NOT NULL,
  `id_carteira` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `login_carteira_antigo`
--

CREATE TABLE IF NOT EXISTS `login_carteira_antigo` (
  `id_login` int(11) NOT NULL,
  `id_carteira` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `login_oline`
--

CREATE TABLE IF NOT EXISTS `login_oline` (
  `id_login_oline` int(11) NOT NULL,
  `ip_login_online` varchar(45) DEFAULT NULL,
  `tempo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `login_perfil`
--

CREATE TABLE IF NOT EXISTS `login_perfil` (
`id_login_perfil` int(11) NOT NULL,
  `nm_login_perfil` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `login_unidade`
--

CREATE TABLE IF NOT EXISTS `login_unidade` (
  `id_login` int(11) NOT NULL,
  `id_unidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Stand-in structure for view `MEDIA`
--
CREATE TABLE IF NOT EXISTS `MEDIA` (
`VAL1` decimal(33,0)
,`VAL2` decimal(33,0)
,`VAL3` decimal(42,0)
,`VAL4` decimal(33,0)
,`VAL5` decimal(33,0)
);
-- --------------------------------------------------------

--
-- Estrutura da tabela `media_tempo_servico`
--

CREATE TABLE IF NOT EXISTS `media_tempo_servico` (
  `SERVICO` varchar(90) DEFAULT NULL,
  `PROTOCOLO` varchar(45) DEFAULT NULL,
  `DT_PROTOCOLADO` date DEFAULT NULL,
  `DT_DISTRIBUIDO` date DEFAULT NULL,
  `DIAS_PROT` int(11) DEFAULT NULL,
  `DT_ANALISE` date DEFAULT NULL,
  `DT_DEFERIDO` date DEFAULT NULL,
  `DIAS_ANALISE` int(11) DEFAULT NULL,
  `DT_REG` date DEFAULT NULL,
  `DT_PRONTO` date DEFAULT NULL,
  `DIAS_PRONTO` int(11) DEFAULT NULL,
  `TOTAL_DIAS` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `modulo`
--

CREATE TABLE IF NOT EXISTS `modulo` (
`id_modulo` int(11) NOT NULL,
  `nm_modulo` varchar(100) DEFAULT NULL,
  `ds_modulo` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `modulo_permissao`
--

CREATE TABLE IF NOT EXISTS `modulo_permissao` (
  `id_modulo` int(11) NOT NULL,
  `id_login` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `nota_informativa`
--

CREATE TABLE IF NOT EXISTS `nota_informativa` (
`id_nota_informativa` int(11) NOT NULL,
  `id_processo` int(11) NOT NULL,
  `id_login` int(11) NOT NULL,
  `dt_nota_informativa` datetime DEFAULT NULL,
  `ds_nota_informativa` varchar(1000) DEFAULT NULL,
  `st_ciente_nota_informativa` int(11) DEFAULT NULL,
  `dt_ciente_nota_informativa` datetime DEFAULT NULL,
  `ip_ciente_nota_informativa` varchar(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=392290 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `parametro`
--

CREATE TABLE IF NOT EXISTS `parametro` (
`id_parametro` int(11) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `id_unidade` int(11) NOT NULL,
  `nm_parametro` varchar(60) DEFAULT NULL,
  `vl_parametro` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=396 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Stand-in structure for view `PERFORMANCE_CARTEIRA`
--
CREATE TABLE IF NOT EXISTS `PERFORMANCE_CARTEIRA` (
`SERVICO` varchar(90)
,`PROTOCOLO` varchar(45)
,`DATA_DE_ENTRADA` date
,`DATA_DE_DISTRIBUICAO` date
,`TEMPO_DE_PROCESSAMENTO` int(11)
,`ENTRADA_EM_ANALISE` date
,`CONCLUSAO_DA_ANALISE` date
,`TEMPO_DE_ANALISE` int(11)
,`TEMPO_DE_RECEBIMENTO_E_ANALISE` bigint(12)
,`ENTRADA_EM_REGISTRO` date
,`DATA_DE_ENTREGA` date
,`TEMPO_DE_REGISTRO` int(11)
,`TEMPO_TOTAL` int(11)
);
-- --------------------------------------------------------

--
-- Estrutura da tabela `posto_graduacao`
--

CREATE TABLE IF NOT EXISTS `posto_graduacao` (
`id_posto_graduacao` int(11) NOT NULL,
  `nm_posto_graduacao` varchar(10) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `processo`
--

CREATE TABLE IF NOT EXISTS `processo` (
`id_processo` int(11) NOT NULL,
  `id_interessado` int(11) NOT NULL,
  `id_unidade` int(11) NOT NULL,
  `id_procurador` int(11) DEFAULT NULL,
  `id_carteira` int(11) NOT NULL,
  `cd_protocolo_processo` varchar(45) DEFAULT NULL,
  `dt_abertura_processo` datetime DEFAULT NULL,
  `dt_arquivamento_processo` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=260605 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `processo_andamento`
--

CREATE TABLE IF NOT EXISTS `processo_andamento` (
`id_processo_andamento` int(11) NOT NULL,
  `id_processo` int(11) NOT NULL,
  `id_login` int(11) NOT NULL,
  `id_processo_status` int(11) NOT NULL,
  `dt_processo_andamento` datetime DEFAULT NULL,
  `obs_processo_andamento` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1527655 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `processo_antigo`
--

CREATE TABLE IF NOT EXISTS `processo_antigo` (
  `id_processo` int(11) NOT NULL DEFAULT '0',
  `id_interessado` int(11) NOT NULL,
  `id_unidade` int(11) NOT NULL,
  `id_procurador` int(11) DEFAULT NULL,
  `id_carteira` int(11) NOT NULL,
  `cd_protocolo_processo` varchar(45) DEFAULT NULL,
  `dt_abertura_processo` datetime DEFAULT NULL,
  `dt_arquivamento_processo` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `processo_servico`
--

CREATE TABLE IF NOT EXISTS `processo_servico` (
  `id_processo` int(11) NOT NULL,
  `id_servico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `processo_status`
--

CREATE TABLE IF NOT EXISTS `processo_status` (
`id_processo_status` int(11) NOT NULL,
  `nm_processo_status` varchar(45) DEFAULT NULL,
  `ds_processo_status` varchar(150) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `procurador`
--

CREATE TABLE IF NOT EXISTS `procurador` (
`id_procurador` int(11) NOT NULL,
  `nm_procurador` varchar(100) DEFAULT NULL,
  `nr_tel_procurador` varchar(20) DEFAULT NULL,
  `email_procurador` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5193 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `relatorio_bkl_sfpc`
--

CREATE TABLE IF NOT EXISTS `relatorio_bkl_sfpc` (
  `CARTEIRA` varchar(100) DEFAULT NULL,
  `ds_servico` varchar(150) DEFAULT NULL,
  `nm_processo_status` varchar(45) DEFAULT NULL,
  `count(1)` bigint(21) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `REPORT_PROCESSO`
--

CREATE TABLE IF NOT EXISTS `REPORT_PROCESSO` (
  `SERVICO` varchar(90) DEFAULT NULL,
  `PROTOCOLO` varchar(45) DEFAULT NULL,
  `DT_PROTOCOLADO` date DEFAULT NULL,
  `DT_DISTRIBUIDO` date DEFAULT NULL,
  `DIAS_PROT` int(11) DEFAULT NULL,
  `DT_ANALISE` date DEFAULT NULL,
  `DT_DEFERIDO` date DEFAULT NULL,
  `DIAS_ANALISE` int(11) DEFAULT NULL,
  `DT_REG` date DEFAULT NULL,
  `DT_PRONTO` date DEFAULT NULL,
  `DIAS_PRONTO` int(11) DEFAULT NULL,
  `TOTAL_DIAS` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `servico`
--

CREATE TABLE IF NOT EXISTS `servico` (
`id_servico` int(11) NOT NULL,
  `id_carteira` int(11) NOT NULL,
  `tp_servico` varchar(45) DEFAULT NULL,
  `ds_servico` varchar(150) DEFAULT NULL,
  `st_pf` int(11) DEFAULT NULL,
  `st_pj` int(11) DEFAULT NULL,
  `st_mil` int(11) DEFAULT NULL,
  `st_mag` int(11) DEFAULT NULL,
  `st_gru` int(11) NOT NULL,
  `id_adt_materia_tipo` int(11) NOT NULL,
  `st_adt_possui_form` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `servico_adt_bkp`
--

CREATE TABLE IF NOT EXISTS `servico_adt_bkp` (
  `id_carteira` int(11) NOT NULL,
  `id_adt_materia_tipo` int(11) NOT NULL,
  `st_adt_possui_form` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `servico_antigo`
--

CREATE TABLE IF NOT EXISTS `servico_antigo` (
  `id_servico` int(11) NOT NULL DEFAULT '0',
  `id_carteira` int(11) NOT NULL,
  `tp_servico` varchar(45) DEFAULT NULL,
  `ds_servico` varchar(150) DEFAULT NULL,
  `st_pf` int(11) DEFAULT NULL,
  `st_pj` int(11) DEFAULT NULL,
  `st_mil` int(11) DEFAULT NULL,
  `st_mag` int(11) DEFAULT NULL,
  `st_gru` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `servico_bkp`
--

CREATE TABLE IF NOT EXISTS `servico_bkp` (
  `id_servico` int(11) NOT NULL DEFAULT '0',
  `id_carteira` int(11) NOT NULL,
  `tp_servico` varchar(45) DEFAULT NULL,
  `ds_servico` varchar(150) DEFAULT NULL,
  `st_pf` int(11) DEFAULT NULL,
  `st_pj` int(11) DEFAULT NULL,
  `st_mil` int(11) DEFAULT NULL,
  `st_mag` int(11) DEFAULT NULL,
  `st_gru` int(11) NOT NULL,
  `id_adt_materia_tipo` int(11) NOT NULL,
  `st_adt_possui_form` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `status_dependencia`
--

CREATE TABLE IF NOT EXISTS `status_dependencia` (
  `id_processo_status` int(11) NOT NULL,
  `id_processo_status_avanca` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Stand-in structure for view `teste_grafana`
--
CREATE TABLE IF NOT EXISTS `teste_grafana` (
`nm_guerra` varchar(45)
,`nm_processo_status` varchar(45)
,`data` date
,`qtd` bigint(21)
);
-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_evento`
--

CREATE TABLE IF NOT EXISTS `tipo_evento` (
`id_tipo_evento` int(11) NOT NULL,
  `ds_tipo_evento` varchar(150) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_interessado`
--

CREATE TABLE IF NOT EXISTS `tipo_interessado` (
  `sg_tipo_interessado` varchar(3) NOT NULL,
  `ds_tipo_interessado` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tiragem_faltas`
--

CREATE TABLE IF NOT EXISTS `tiragem_faltas` (
  `dia` datetime DEFAULT NULL,
  `militar` varchar(25) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `id` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `unidade`
--

CREATE TABLE IF NOT EXISTS `unidade` (
`id_unidade` int(11) NOT NULL,
  `id_cidade` int(11) NOT NULL,
  `nr_unidade` varchar(2) DEFAULT NULL,
  `nm_unidade` varchar(60) DEFAULT NULL,
  `ds_endereco_unidade` varchar(150) DEFAULT NULL,
  `email_unidade` varchar(60) DEFAULT NULL,
  `cd_fone_unidade` varchar(60) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) unsigned NOT NULL,
  `id_cidade` int(11) NOT NULL,
  `id_agendamento_login_tipo` int(11) NOT NULL,
  `cpf_login` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nm_completo` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nr_celular` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `st_login` int(11) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure for view `grafico_tempo_servico`
--
DROP TABLE IF EXISTS `grafico_tempo_servico`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sistemas_sfpc`@`%` SQL SECURITY DEFINER VIEW `grafico_tempo_servico` AS select `rp`.`SERVICO` AS `SERVICO`,`rp`.`PROTOCOLO` AS `PROTOCOLO`,`rp`.`DT_PROTOCOLADO` AS `DATA_DE_ENTRADA`,`rp`.`DT_DISTRIBUIDO` AS `DATA_DE_DISTRIBUICAO`,`rp`.`DIAS_PROT` AS `TEMPO_DE_PROCESSAMENTO`,`rp`.`DT_ANALISE` AS `ENTRADA_EM_ANALISE`,`rp`.`DT_DEFERIDO` AS `CONCLUSAO_DA_ANALISE`,`rp`.`DIAS_ANALISE` AS `TEMPO_DE_ANALISE`,(`rp`.`DIAS_PROT` + `rp`.`DIAS_ANALISE`) AS `TEMPO_DE_RECEBIMENTO_E_ANALISE`,`rp`.`DT_REG` AS `ENTRADA_EM_REGISTRO`,`rp`.`DT_PRONTO` AS `DATA_DE_ENTREGA`,`rp`.`DIAS_PRONTO` AS `TEMPO_DE_REGISTRO`,`rp`.`TOTAL_DIAS` AS `TEMPO_TOTAL` from `media_tempo_servico` `rp` order by `rp`.`SERVICO`,`rp`.`TOTAL_DIAS` desc;

-- --------------------------------------------------------

--
-- Structure for view `MEDIA`
--
DROP TABLE IF EXISTS `MEDIA`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sistemas_sfpc`@`%` SQL SECURITY DEFINER VIEW `MEDIA` AS select round((sum(`PERFORMANCE_CARTEIRA`.`TEMPO_DE_PROCESSAMENTO`) / count(1)),0) AS `VAL1`,round((sum(`PERFORMANCE_CARTEIRA`.`TEMPO_DE_ANALISE`) / count(1)),0) AS `VAL2`,round((sum(`PERFORMANCE_CARTEIRA`.`TEMPO_DE_RECEBIMENTO_E_ANALISE`) / count(1)),0) AS `VAL3`,round((sum(`PERFORMANCE_CARTEIRA`.`TEMPO_DE_REGISTRO`) / count(1)),0) AS `VAL4`,round((sum(`PERFORMANCE_CARTEIRA`.`TEMPO_TOTAL`) / count(1)),0) AS `VAL5` from `PERFORMANCE_CARTEIRA`;

-- --------------------------------------------------------

--
-- Structure for view `PERFORMANCE_CARTEIRA`
--
DROP TABLE IF EXISTS `PERFORMANCE_CARTEIRA`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sistemas_sfpc`@`%` SQL SECURITY DEFINER VIEW `PERFORMANCE_CARTEIRA` AS select `rp`.`SERVICO` AS `SERVICO`,`rp`.`PROTOCOLO` AS `PROTOCOLO`,`rp`.`DT_PROTOCOLADO` AS `DATA_DE_ENTRADA`,`rp`.`DT_DISTRIBUIDO` AS `DATA_DE_DISTRIBUICAO`,`rp`.`DIAS_PROT` AS `TEMPO_DE_PROCESSAMENTO`,`rp`.`DT_ANALISE` AS `ENTRADA_EM_ANALISE`,`rp`.`DT_DEFERIDO` AS `CONCLUSAO_DA_ANALISE`,`rp`.`DIAS_ANALISE` AS `TEMPO_DE_ANALISE`,(`rp`.`DIAS_PROT` + `rp`.`DIAS_ANALISE`) AS `TEMPO_DE_RECEBIMENTO_E_ANALISE`,`rp`.`DT_REG` AS `ENTRADA_EM_REGISTRO`,`rp`.`DT_PRONTO` AS `DATA_DE_ENTREGA`,`rp`.`DIAS_PRONTO` AS `TEMPO_DE_REGISTRO`,`rp`.`TOTAL_DIAS` AS `TEMPO_TOTAL` from `REPORT_PROCESSO` `rp` order by `rp`.`SERVICO`,`rp`.`TOTAL_DIAS` desc;

-- --------------------------------------------------------

--
-- Structure for view `teste_grafana`
--
DROP TABLE IF EXISTS `teste_grafana`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sistemas_sfpc`@`%` SQL SECURITY DEFINER VIEW `teste_grafana` AS select `l`.`nm_guerra` AS `nm_guerra`,`ps`.`nm_processo_status` AS `nm_processo_status`,cast(`pa`.`dt_processo_andamento` as date) AS `data`,count(1) AS `qtd` from ((`processo_andamento` `pa` join `login` `l`) join `processo_status` `ps`) where ((`pa`.`id_login` = `l`.`id_login`) and (`pa`.`id_processo_status` = `ps`.`id_processo_status`) and (`pa`.`id_processo_status` in (3,6,7,8)) and (`pa`.`dt_processo_andamento` >= (cast(now() as date) - interval 7 day)) and (`l`.`id_unidade` = 1)) group by `pa`.`id_login`,`ps`.`nm_processo_status`,cast(`pa`.`dt_processo_andamento` as date) order by cast(`pa`.`dt_processo_andamento` as date),`l`.`nm_guerra`;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adt`
--
ALTER TABLE `adt`
 ADD PRIMARY KEY (`id_adt`,`id_adt_status`), ADD KEY `fk_aditamento_aditamento_status1_idx` (`id_adt_status`);

--
-- Indexes for table `adt_acervo`
--
ALTER TABLE `adt_acervo`
 ADD PRIMARY KEY (`id_adt_acervo`);

--
-- Indexes for table `adt_arma`
--
ALTER TABLE `adt_arma`
 ADD PRIMARY KEY (`id_adt_arma`,`id_adt_arma_modelo`,`id_adt_arma_pais_origem`,`id_adt_arma_acabamento`), ADD KEY `fk_arma_arma_modelo1_idx` (`id_adt_arma_modelo`), ADD KEY `fk_arma_arma_pais_origem1_idx` (`id_adt_arma_pais_origem`), ADD KEY `fk_arma_arma_acabamento1_idx` (`id_adt_arma_acabamento`);

--
-- Indexes for table `adt_arma_acabamento`
--
ALTER TABLE `adt_arma_acabamento`
 ADD PRIMARY KEY (`id_adt_arma_acabamento`);

--
-- Indexes for table `adt_arma_alma`
--
ALTER TABLE `adt_arma_alma`
 ADD PRIMARY KEY (`id_adt_arma_alma`);

--
-- Indexes for table `adt_arma_calibre`
--
ALTER TABLE `adt_arma_calibre`
 ADD PRIMARY KEY (`id_adt_arma_calibre`);

--
-- Indexes for table `adt_arma_especie`
--
ALTER TABLE `adt_arma_especie`
 ADD PRIMARY KEY (`id_adt_arma_especie`);

--
-- Indexes for table `adt_arma_fornecedor`
--
ALTER TABLE `adt_arma_fornecedor`
 ADD PRIMARY KEY (`id_adt_arma_fornecedor`);

--
-- Indexes for table `adt_arma_funcionamento`
--
ALTER TABLE `adt_arma_funcionamento`
 ADD PRIMARY KEY (`id_adt_arma_funcionamento`);

--
-- Indexes for table `adt_arma_marca`
--
ALTER TABLE `adt_arma_marca`
 ADD PRIMARY KEY (`id_adt_arma_marca`);

--
-- Indexes for table `adt_arma_modelo`
--
ALTER TABLE `adt_arma_modelo`
 ADD PRIMARY KEY (`id_adt_arma_modelo`,`id_adt_arma_marca`,`id_adt_arma_especie`,`id_adt_arma_calibre`,`id_adt_arma_funcionamento`), ADD KEY `fk_arma_modelo_arma_marca1_idx` (`id_adt_arma_marca`), ADD KEY `fk_arma_modelo_arma_especie1_idx` (`id_adt_arma_especie`), ADD KEY `fk_arma_modelo_arma_calibre1_idx` (`id_adt_arma_calibre`), ADD KEY `fk_arma_modelo_arma_funcionamento1_idx` (`id_adt_arma_funcionamento`), ADD KEY `fk_adt_arma_modelo_adt_arma_alma` (`id_adt_arma_alma`);

--
-- Indexes for table `adt_arma_pais_origem`
--
ALTER TABLE `adt_arma_pais_origem`
 ADD PRIMARY KEY (`id_adt_arma_pais_origem`);

--
-- Indexes for table `adt_assinaturas`
--
ALTER TABLE `adt_assinaturas`
 ADD PRIMARY KEY (`nm_adt_assinaturas`);

--
-- Indexes for table `adt_atividade_cr`
--
ALTER TABLE `adt_atividade_cr`
 ADD PRIMARY KEY (`id_adt_atividade_cr`);

--
-- Indexes for table `adt_cr`
--
ALTER TABLE `adt_cr`
 ADD PRIMARY KEY (`id_adt_cr`,`id_interessado`), ADD KEY `fk_cr_interessado1_idx` (`id_interessado`);

--
-- Indexes for table `adt_materia`
--
ALTER TABLE `adt_materia`
 ADD PRIMARY KEY (`id_adt_materia`,`id_adt_materia_tipo`), ADD KEY `fk_materia_materia_tipo1_idx` (`id_adt_materia_tipo`), ADD KEY `fk_adt_materia_processo1_idx` (`id_processo`);

--
-- Indexes for table `adt_materia_andamento`
--
ALTER TABLE `adt_materia_andamento`
 ADD PRIMARY KEY (`id_adt_materia_andamento`,`id_adt_materia`,`id_adt_materia_status`,`id_login`), ADD KEY `fk_adt_materia_andamento_adt_materia_status1_idx` (`id_adt_materia_status`), ADD KEY `fk_adt_materia_andamento_login1_idx` (`id_login`), ADD KEY `fk_adt_materia_andamento_adt_materia1_idx` (`id_adt_materia`);

--
-- Indexes for table `adt_materia_arma`
--
ALTER TABLE `adt_materia_arma`
 ADD PRIMARY KEY (`id_adt_materia`,`id_adt_arma`,`id_adt_acervo`), ADD KEY `fk_materia_arma_materia1_idx` (`id_adt_materia`), ADD KEY `fk_materia_arma_arma1_idx` (`id_adt_arma`), ADD KEY `fk_materia_arma_acervo1_idx` (`id_adt_acervo`), ADD KEY `fk_materia_arma_arma_fornecedor1_idx` (`id_adt_arma_fornecedor`), ADD KEY `fk_materia_arma_interessado1_idx` (`id_interessado_cedente`);

--
-- Indexes for table `adt_materia_cr_atividade`
--
ALTER TABLE `adt_materia_cr_atividade`
 ADD PRIMARY KEY (`id_adt_materia`,`id_adt_atividade_cr`), ADD KEY `fk_materia_cr_atividade_atividade_cr1_idx` (`id_adt_atividade_cr`), ADD KEY `fk_materia_cr_atividade_materia1_idx` (`id_adt_materia`);

--
-- Indexes for table `adt_materia_status`
--
ALTER TABLE `adt_materia_status`
 ADD PRIMARY KEY (`id_adt_materia_status`);

--
-- Indexes for table `adt_materia_tipo`
--
ALTER TABLE `adt_materia_tipo`
 ADD PRIMARY KEY (`id_adt_materia_tipo`);

--
-- Indexes for table `adt_materia_vinc_rm`
--
ALTER TABLE `adt_materia_vinc_rm`
 ADD PRIMARY KEY (`id_adt_materia`);

--
-- Indexes for table `agendamento_assunto`
--
ALTER TABLE `agendamento_assunto`
 ADD PRIMARY KEY (`id_agendamento_assunto`);

--
-- Indexes for table `agendamento_assunto_horario`
--
ALTER TABLE `agendamento_assunto_horario`
 ADD PRIMARY KEY (`id_agendamento_horario`,`id_agendamento_assunto`), ADD KEY `fk_agendamento_atendente_horario_agendamento_horario1_idx` (`id_agendamento_horario`), ADD KEY `fk_agendamento_assunto_horario_agendamento_assunto1_idx` (`id_agendamento_assunto`);

--
-- Indexes for table `agendamento_data`
--
ALTER TABLE `agendamento_data`
 ADD PRIMARY KEY (`id_agendamento_data`,`id_login`,`unidade_id_unidade`), ADD KEY `fk_agendamento_login1_idx` (`id_login`), ADD KEY `fk_agendamento_unidade1_idx` (`unidade_id_unidade`);

--
-- Indexes for table `agendamento_horario`
--
ALTER TABLE `agendamento_horario`
 ADD PRIMARY KEY (`id_agendamento_horario`,`id_agendamento_data`), ADD KEY `fk_agendamento_horario_agendamento1_idx` (`id_agendamento_data`);

--
-- Indexes for table `agendamento_horario_interessado`
--
ALTER TABLE `agendamento_horario_interessado`
 ADD PRIMARY KEY (`id_agendamento_horario_interessado`,`id_agendamento_horario`,`id_agendamento_login`,`id_interessado`), ADD KEY `fk_agendamento_processo_requerente_horario_agendamento_hora_idx` (`id_agendamento_horario`), ADD KEY `fk_agendamento_horario_interessado_agendamento_login1_idx` (`id_agendamento_login`), ADD KEY `fk_agendamento_horario_interessado_interessado1_idx` (`id_interessado`);

--
-- Indexes for table `agendamento_login`
--
ALTER TABLE `agendamento_login`
 ADD PRIMARY KEY (`id_agendamento_login`,`id_cidade`,`id_agendamento_login_tipo`,`id_arquivo`), ADD KEY `fk_agendamento_login_cidade1_idx` (`id_cidade`), ADD KEY `fk_agendamento_login_arquivo1_idx` (`id_arquivo`), ADD KEY `fk_agendamento_login_agendamento_login_tipo1_idx` (`id_agendamento_login_tipo`);

--
-- Indexes for table `agendamento_login_historico`
--
ALTER TABLE `agendamento_login_historico`
 ADD PRIMARY KEY (`id_agendamento_login_historico`,`id_agendamento_login`,`id_agendamento_login_status`), ADD KEY `fk_agendamento_login_historico_agendamento_login_status1_idx` (`id_agendamento_login_status`), ADD KEY `fk_agendamento_login_historico_agendamento_login1_idx` (`id_agendamento_login`);

--
-- Indexes for table `agendamento_login_status`
--
ALTER TABLE `agendamento_login_status`
 ADD PRIMARY KEY (`id_agendamento_login_status`);

--
-- Indexes for table `agendamento_login_tipo`
--
ALTER TABLE `agendamento_login_tipo`
 ADD PRIMARY KEY (`id_agendamento_login_tipo`);

--
-- Indexes for table `agendamento_requerente`
--
ALTER TABLE `agendamento_requerente`
 ADD PRIMARY KEY (`id_agendamento_requerente`,`id_agendamento_horario`,`id_agendamento_login`), ADD KEY `fk_agendamento_requerente_agendamento_login_requerente1_idx` (`id_agendamento_login`), ADD KEY `fk_agendamento_requerente_agendamento_horario1_idx` (`id_agendamento_horario`), ADD KEY `fk_agendamento_requerente_agendamento_assunto1` (`id_agendamento_assunto`);

--
-- Indexes for table `agendamento_requerente_andamento`
--
ALTER TABLE `agendamento_requerente_andamento`
 ADD PRIMARY KEY (`id_agendamento_requerente_andamento`,`id_agendamento_requerente`,`id_agendamento_status`), ADD KEY `fk_agendamento_requerente_andamento_agendamento_requerente1_idx` (`id_agendamento_requerente`), ADD KEY `fk_agendamento_requerente_andamento_agendamento_status1_idx` (`id_agendamento_status`);

--
-- Indexes for table `agendamento_status`
--
ALTER TABLE `agendamento_status`
 ADD PRIMARY KEY (`id_agendamento_status`);

--
-- Indexes for table `agendamento_tipo_usuario_horario`
--
ALTER TABLE `agendamento_tipo_usuario_horario`
 ADD PRIMARY KEY (`id_agendamento_horario`,`id_agendamento_login_tipo`), ADD KEY `fk_agendamento_tipo_usuario_horario_agendamento_login_tipo1_idx` (`id_agendamento_login_tipo`);

--
-- Indexes for table `agendamento_unidade_cidade`
--
ALTER TABLE `agendamento_unidade_cidade`
 ADD PRIMARY KEY (`id_unidade`,`id_cidade`), ADD KEY `fk_agendamento_cidade_unidade_cidade1_idx` (`id_cidade`);

--
-- Indexes for table `arquivo`
--
ALTER TABLE `arquivo`
 ADD PRIMARY KEY (`id_arquivo`,`id_modulo`), ADD KEY `fk_arquivo_modulo1_idx` (`id_modulo`);

--
-- Indexes for table `carteira`
--
ALTER TABLE `carteira`
 ADD PRIMARY KEY (`id_carteira`);

--
-- Indexes for table `cidade`
--
ALTER TABLE `cidade`
 ADD PRIMARY KEY (`id_cidade`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
 ADD PRIMARY KEY (`ID`), ADD KEY `CountryCode` (`CountryCode`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
 ADD PRIMARY KEY (`Code`);

--
-- Indexes for table `countrylanguage`
--
ALTER TABLE `countrylanguage`
 ADD PRIMARY KEY (`CountryCode`,`Language`), ADD KEY `CountryCode` (`CountryCode`);

--
-- Indexes for table `documento`
--
ALTER TABLE `documento`
 ADD PRIMARY KEY (`id_documento`,`id_documento_tipo`,`id_login`), ADD KEY `fk_documento_documento_tipo1_idx` (`id_documento_tipo`), ADD KEY `fk_documento_login1_idx` (`id_login`), ADD KEY `fk_documento_processo1_idx` (`id_processo`), ADD KEY `fk_documento_carteira1_idx` (`id_carteira`), ADD KEY `fk_documento_servico1_idx` (`id_servico`);

--
-- Indexes for table `documento_indexador`
--
ALTER TABLE `documento_indexador`
 ADD PRIMARY KEY (`id_documento_indexador`,`id_documento_indexador_formato`), ADD KEY `fk_documento_indexador_documento_tipo_campo1_idx` (`id_documento_indexador_formato`);

--
-- Indexes for table `documento_indexador_formato`
--
ALTER TABLE `documento_indexador_formato`
 ADD PRIMARY KEY (`id_documento_indexador_formato`);

--
-- Indexes for table `documento_tipo`
--
ALTER TABLE `documento_tipo`
 ADD PRIMARY KEY (`id_documento_tipo`);

--
-- Indexes for table `documento_tipo_indexadores`
--
ALTER TABLE `documento_tipo_indexadores`
 ADD PRIMARY KEY (`id_documento_tipo`,`id_documento_indexador`), ADD KEY `fk_documento_tipo_indexadores_documento_indexador1_idx` (`id_documento_indexador`);

--
-- Indexes for table `documento_valor`
--
ALTER TABLE `documento_valor`
 ADD PRIMARY KEY (`id_documento`,`id_documento_indexador`), ADD KEY `fk_documento_dados_documento1_idx` (`id_documento`), ADD KEY `fk_documento_valor_documento_indexador1_idx` (`id_documento_indexador`), ADD KEY `idx_documento_valor_valor` (`valor`);

--
-- Indexes for table `evento`
--
ALTER TABLE `evento`
 ADD PRIMARY KEY (`id_evento`,`id_tipo_evento`), ADD KEY `fk_evento_tipo_evento1_idx` (`id_tipo_evento`);

--
-- Indexes for table `gru`
--
ALTER TABLE `gru`
 ADD PRIMARY KEY (`id_gru`);

--
-- Indexes for table `gru_processo`
--
ALTER TABLE `gru_processo`
 ADD PRIMARY KEY (`id_gru`,`id_processo`), ADD KEY `fk_gru_processo_processo1_idx` (`id_processo`);

--
-- Indexes for table `gru_tentativa_fraude`
--
ALTER TABLE `gru_tentativa_fraude`
 ADD PRIMARY KEY (`dt_gru_tentativa_fraude`,`id_gru`,`id_login`,`id_interessado`), ADD KEY `fk_gru_tentativa_fraude_gru1_idx` (`id_gru`), ADD KEY `fk_gru_tentativa_fraude_login1_idx` (`id_login`), ADD KEY `fk_gru_tentativa_fraude_interessado1_idx` (`id_interessado`), ADD KEY `fk_gru_tentativa_fraude_procurador1_idx` (`id_procurador`);

--
-- Indexes for table `interessado`
--
ALTER TABLE `interessado`
 ADD PRIMARY KEY (`id_interessado`,`id_cidade`,`sg_tipo_interessado`), ADD KEY `fk_interessado_cidade1_idx` (`id_cidade`), ADD KEY `fk_interessado_tipo_interessado1_idx` (`sg_tipo_interessado`), ADD KEY `idx_interessado_cpf_interessado` (`cpf_interessado`), ADD KEY `idx_interessado_cpf` (`cpf_interessado`), ADD KEY `idx_cpf` (`cpf_interessado`), ADD KEY `idx_cpf_interessado` (`cpf_interessado`), ADD KEY `idx_cnpj_interessado` (`cnpj_interessado`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
 ADD PRIMARY KEY (`id_login`,`id_posto_graduacao`,`id_login_perfil`,`id_unidade`), ADD KEY `fk_login_posto_graduacao1_idx` (`id_posto_graduacao`), ADD KEY `fk_login_unidade1_idx` (`id_unidade`), ADD KEY `fk_login_login_perfil1_idx` (`id_login_perfil`);

--
-- Indexes for table `login_carteira`
--
ALTER TABLE `login_carteira`
 ADD PRIMARY KEY (`id_login`,`id_carteira`), ADD KEY `fk_acesso_carteira_carteira1_idx` (`id_carteira`), ADD KEY `fk_acesso_carteira_login1_idx` (`id_login`);

--
-- Indexes for table `login_oline`
--
ALTER TABLE `login_oline`
 ADD PRIMARY KEY (`id_login_oline`);

--
-- Indexes for table `login_perfil`
--
ALTER TABLE `login_perfil`
 ADD PRIMARY KEY (`id_login_perfil`);

--
-- Indexes for table `login_unidade`
--
ALTER TABLE `login_unidade`
 ADD PRIMARY KEY (`id_login`,`id_unidade`), ADD KEY `fk_login_unidade_unidade1_idx` (`id_unidade`);

--
-- Indexes for table `modulo`
--
ALTER TABLE `modulo`
 ADD PRIMARY KEY (`id_modulo`);

--
-- Indexes for table `modulo_permissao`
--
ALTER TABLE `modulo_permissao`
 ADD PRIMARY KEY (`id_modulo`,`id_login`), ADD KEY `fk_modulo_permissao_login1_idx` (`id_login`);

--
-- Indexes for table `nota_informativa`
--
ALTER TABLE `nota_informativa`
 ADD PRIMARY KEY (`id_nota_informativa`,`id_processo`,`id_login`), ADD KEY `fk_nota_informativa_login1_idx` (`id_login`), ADD KEY `fk_nota_informativa_processo1` (`id_processo`);

--
-- Indexes for table `parametro`
--
ALTER TABLE `parametro`
 ADD PRIMARY KEY (`id_parametro`,`id_modulo`,`id_unidade`), ADD KEY `fk_parametros_modulo1_idx` (`id_modulo`), ADD KEY `fk_parametro_unidade1_idx` (`id_unidade`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
 ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `posto_graduacao`
--
ALTER TABLE `posto_graduacao`
 ADD PRIMARY KEY (`id_posto_graduacao`);

--
-- Indexes for table `processo`
--
ALTER TABLE `processo`
 ADD PRIMARY KEY (`id_processo`,`id_interessado`,`id_unidade`,`id_carteira`), ADD KEY `fk_protocolo_interessado1_idx` (`id_interessado`), ADD KEY `fk_processo_unidade1_idx` (`id_unidade`), ADD KEY `fk_processo_procurador1_idx` (`id_procurador`), ADD KEY `fk_processo_carteira1_idx` (`id_carteira`), ADD KEY `idx_processo_cd_protocolo_processo` (`cd_protocolo_processo`);

--
-- Indexes for table `processo_andamento`
--
ALTER TABLE `processo_andamento`
 ADD PRIMARY KEY (`id_processo_andamento`,`id_processo`,`id_login`,`id_processo_status`), ADD KEY `fk_andamento_protocolo_login1_idx` (`id_login`), ADD KEY `fk_andamento_processo_processo1_idx` (`id_processo`), ADD KEY `fk_processo_andamento_processo_status1_idx` (`id_processo_status`);

--
-- Indexes for table `processo_servico`
--
ALTER TABLE `processo_servico`
 ADD PRIMARY KEY (`id_processo`,`id_servico`), ADD KEY `fk_processo_servico_processo1_idx` (`id_processo`), ADD KEY `fk_processo_servico_servico1` (`id_servico`);

--
-- Indexes for table `processo_status`
--
ALTER TABLE `processo_status`
 ADD PRIMARY KEY (`id_processo_status`);

--
-- Indexes for table `procurador`
--
ALTER TABLE `procurador`
 ADD PRIMARY KEY (`id_procurador`);

--
-- Indexes for table `servico`
--
ALTER TABLE `servico`
 ADD PRIMARY KEY (`id_servico`,`id_carteira`), ADD KEY `fk_servico_carteira1_idx` (`id_carteira`);

--
-- Indexes for table `status_dependencia`
--
ALTER TABLE `status_dependencia`
 ADD PRIMARY KEY (`id_processo_status`,`id_processo_status_avanca`), ADD KEY `fk_status_dependencia_processo_status2_idx` (`id_processo_status_avanca`);

--
-- Indexes for table `tipo_evento`
--
ALTER TABLE `tipo_evento`
 ADD PRIMARY KEY (`id_tipo_evento`);

--
-- Indexes for table `tipo_interessado`
--
ALTER TABLE `tipo_interessado`
 ADD PRIMARY KEY (`sg_tipo_interessado`);

--
-- Indexes for table `unidade`
--
ALTER TABLE `unidade`
 ADD PRIMARY KEY (`id_unidade`,`id_cidade`), ADD KEY `fk_rede_sfpc_cidade1_idx` (`id_cidade`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `users_cpf_login_unique` (`cpf_login`), ADD UNIQUE KEY `users_email_unique` (`email`), ADD KEY `users_id_cidade_foreign` (`id_cidade`), ADD KEY `users_id_agendamento_login_tipo_foreign` (`id_agendamento_login_tipo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adt`
--
ALTER TABLE `adt`
MODIFY `id_adt` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `adt_acervo`
--
ALTER TABLE `adt_acervo`
MODIFY `id_adt_acervo` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `adt_arma`
--
ALTER TABLE `adt_arma`
MODIFY `id_adt_arma` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=746;
--
-- AUTO_INCREMENT for table `adt_arma_acabamento`
--
ALTER TABLE `adt_arma_acabamento`
MODIFY `id_adt_arma_acabamento` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `adt_arma_alma`
--
ALTER TABLE `adt_arma_alma`
MODIFY `id_adt_arma_alma` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `adt_arma_calibre`
--
ALTER TABLE `adt_arma_calibre`
MODIFY `id_adt_arma_calibre` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `adt_arma_especie`
--
ALTER TABLE `adt_arma_especie`
MODIFY `id_adt_arma_especie` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `adt_arma_fornecedor`
--
ALTER TABLE `adt_arma_fornecedor`
MODIFY `id_adt_arma_fornecedor` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT for table `adt_arma_funcionamento`
--
ALTER TABLE `adt_arma_funcionamento`
MODIFY `id_adt_arma_funcionamento` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `adt_arma_marca`
--
ALTER TABLE `adt_arma_marca`
MODIFY `id_adt_arma_marca` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT for table `adt_arma_modelo`
--
ALTER TABLE `adt_arma_modelo`
MODIFY `id_adt_arma_modelo` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=187;
--
-- AUTO_INCREMENT for table `adt_arma_pais_origem`
--
ALTER TABLE `adt_arma_pais_origem`
MODIFY `id_adt_arma_pais_origem` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `adt_atividade_cr`
--
ALTER TABLE `adt_atividade_cr`
MODIFY `id_adt_atividade_cr` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `adt_cr`
--
ALTER TABLE `adt_cr`
MODIFY `id_adt_cr` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=286;
--
-- AUTO_INCREMENT for table `adt_materia`
--
ALTER TABLE `adt_materia`
MODIFY `id_adt_materia` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2162;
--
-- AUTO_INCREMENT for table `adt_materia_andamento`
--
ALTER TABLE `adt_materia_andamento`
MODIFY `id_adt_materia_andamento` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3438;
--
-- AUTO_INCREMENT for table `adt_materia_status`
--
ALTER TABLE `adt_materia_status`
MODIFY `id_adt_materia_status` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `adt_materia_tipo`
--
ALTER TABLE `adt_materia_tipo`
MODIFY `id_adt_materia_tipo` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=122;
--
-- AUTO_INCREMENT for table `agendamento_assunto`
--
ALTER TABLE `agendamento_assunto`
MODIFY `id_agendamento_assunto` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `agendamento_data`
--
ALTER TABLE `agendamento_data`
MODIFY `id_agendamento_data` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7291;
--
-- AUTO_INCREMENT for table `agendamento_horario`
--
ALTER TABLE `agendamento_horario`
MODIFY `id_agendamento_horario` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=93517;
--
-- AUTO_INCREMENT for table `agendamento_horario_interessado`
--
ALTER TABLE `agendamento_horario_interessado`
MODIFY `id_agendamento_horario_interessado` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `agendamento_login`
--
ALTER TABLE `agendamento_login`
MODIFY `id_agendamento_login` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19966;
--
-- AUTO_INCREMENT for table `agendamento_login_historico`
--
ALTER TABLE `agendamento_login_historico`
MODIFY `id_agendamento_login_historico` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=55895;
--
-- AUTO_INCREMENT for table `agendamento_login_status`
--
ALTER TABLE `agendamento_login_status`
MODIFY `id_agendamento_login_status` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `agendamento_login_tipo`
--
ALTER TABLE `agendamento_login_tipo`
MODIFY `id_agendamento_login_tipo` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `agendamento_requerente`
--
ALTER TABLE `agendamento_requerente`
MODIFY `id_agendamento_requerente` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=120221;
--
-- AUTO_INCREMENT for table `agendamento_requerente_andamento`
--
ALTER TABLE `agendamento_requerente_andamento`
MODIFY `id_agendamento_requerente_andamento` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=202476;
--
-- AUTO_INCREMENT for table `agendamento_status`
--
ALTER TABLE `agendamento_status`
MODIFY `id_agendamento_status` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `arquivo`
--
ALTER TABLE `arquivo`
MODIFY `id_arquivo` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20743;
--
-- AUTO_INCREMENT for table `carteira`
--
ALTER TABLE `carteira`
MODIFY `id_carteira` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `cidade`
--
ALTER TABLE `cidade`
MODIFY `id_cidade` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=936;
--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4080;
--
-- AUTO_INCREMENT for table `documento`
--
ALTER TABLE `documento`
MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27478;
--
-- AUTO_INCREMENT for table `documento_indexador`
--
ALTER TABLE `documento_indexador`
MODIFY `id_documento_indexador` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `documento_indexador_formato`
--
ALTER TABLE `documento_indexador_formato`
MODIFY `id_documento_indexador_formato` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `documento_tipo`
--
ALTER TABLE `documento_tipo`
MODIFY `id_documento_tipo` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `evento`
--
ALTER TABLE `evento`
MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=36579666;
--
-- AUTO_INCREMENT for table `gru`
--
ALTER TABLE `gru`
MODIFY `id_gru` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37659;
--
-- AUTO_INCREMENT for table `interessado`
--
ALTER TABLE `interessado`
MODIFY `id_interessado` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=96709;
--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
MODIFY `id_login` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=496;
--
-- AUTO_INCREMENT for table `login_perfil`
--
ALTER TABLE `login_perfil`
MODIFY `id_login_perfil` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `modulo`
--
ALTER TABLE `modulo`
MODIFY `id_modulo` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `nota_informativa`
--
ALTER TABLE `nota_informativa`
MODIFY `id_nota_informativa` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=392290;
--
-- AUTO_INCREMENT for table `parametro`
--
ALTER TABLE `parametro`
MODIFY `id_parametro` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=396;
--
-- AUTO_INCREMENT for table `posto_graduacao`
--
ALTER TABLE `posto_graduacao`
MODIFY `id_posto_graduacao` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `processo`
--
ALTER TABLE `processo`
MODIFY `id_processo` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=260605;
--
-- AUTO_INCREMENT for table `processo_andamento`
--
ALTER TABLE `processo_andamento`
MODIFY `id_processo_andamento` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1527655;
--
-- AUTO_INCREMENT for table `processo_status`
--
ALTER TABLE `processo_status`
MODIFY `id_processo_status` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `procurador`
--
ALTER TABLE `procurador`
MODIFY `id_procurador` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5193;
--
-- AUTO_INCREMENT for table `servico`
--
ALTER TABLE `servico`
MODIFY `id_servico` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=121;
--
-- AUTO_INCREMENT for table `tipo_evento`
--
ALTER TABLE `tipo_evento`
MODIFY `id_tipo_evento` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `unidade`
--
ALTER TABLE `unidade`
MODIFY `id_unidade` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `agendamento_assunto_horario`
--
ALTER TABLE `agendamento_assunto_horario`
ADD CONSTRAINT `fk_agendamento_assunto_horario_agendamento_assunto1` FOREIGN KEY (`id_agendamento_assunto`) REFERENCES `agendamento_assunto` (`id_agendamento_assunto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_agendamento_atendente_horario_agendamento_horario1` FOREIGN KEY (`id_agendamento_horario`) REFERENCES `agendamento_horario` (`id_agendamento_horario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `agendamento_data`
--
ALTER TABLE `agendamento_data`
ADD CONSTRAINT `fk_agendamento_login1` FOREIGN KEY (`id_login`) REFERENCES `login` (`id_login`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_agendamento_unidade1` FOREIGN KEY (`unidade_id_unidade`) REFERENCES `unidade` (`id_unidade`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `agendamento_horario`
--
ALTER TABLE `agendamento_horario`
ADD CONSTRAINT `fk_agendamento_horario_agendamento1` FOREIGN KEY (`id_agendamento_data`) REFERENCES `agendamento_data` (`id_agendamento_data`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `agendamento_horario_interessado`
--
ALTER TABLE `agendamento_horario_interessado`
ADD CONSTRAINT `fk_agendamento_processo_requerente_horario_agendamento_horario1` FOREIGN KEY (`id_agendamento_horario`) REFERENCES `agendamento_horario` (`id_agendamento_horario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_agendamento_horario_interessado_agendamento_login1` FOREIGN KEY (`id_agendamento_login`) REFERENCES `agendamento_login` (`id_agendamento_login`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_agendamento_horario_interessado_interessado1` FOREIGN KEY (`id_interessado`) REFERENCES `interessado` (`id_interessado`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `agendamento_login`
--
ALTER TABLE `agendamento_login`
ADD CONSTRAINT `fk_agendamento_login_agendamento_login_tipo1` FOREIGN KEY (`id_agendamento_login_tipo`) REFERENCES `agendamento_login_tipo` (`id_agendamento_login_tipo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_agendamento_login_arquivo1` FOREIGN KEY (`id_arquivo`) REFERENCES `arquivo` (`id_arquivo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_agendamento_login_cidade1` FOREIGN KEY (`id_cidade`) REFERENCES `cidade` (`id_cidade`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `agendamento_login_historico`
--
ALTER TABLE `agendamento_login_historico`
ADD CONSTRAINT `fk_agendamento_login_historico_agendamento_login1` FOREIGN KEY (`id_agendamento_login`) REFERENCES `agendamento_login` (`id_agendamento_login`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_agendamento_login_historico_agendamento_login_status1` FOREIGN KEY (`id_agendamento_login_status`) REFERENCES `agendamento_login_status` (`id_agendamento_login_status`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `agendamento_requerente`
--
ALTER TABLE `agendamento_requerente`
ADD CONSTRAINT `fk_agendamento_requerente_agendamento_assunto1` FOREIGN KEY (`id_agendamento_assunto`) REFERENCES `agendamento_assunto` (`id_agendamento_assunto`),
ADD CONSTRAINT `fk_agendamento_requerente_agendamento_horario1` FOREIGN KEY (`id_agendamento_horario`) REFERENCES `agendamento_horario` (`id_agendamento_horario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_agendamento_requerente_agendamento_login_requerente1` FOREIGN KEY (`id_agendamento_login`) REFERENCES `agendamento_login` (`id_agendamento_login`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `agendamento_requerente_andamento`
--
ALTER TABLE `agendamento_requerente_andamento`
ADD CONSTRAINT `fk_agendamento_requerente_andamento_agendamento_requerente1` FOREIGN KEY (`id_agendamento_requerente`) REFERENCES `agendamento_requerente` (`id_agendamento_requerente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_agendamento_requerente_andamento_agendamento_status1` FOREIGN KEY (`id_agendamento_status`) REFERENCES `agendamento_status` (`id_agendamento_status`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `agendamento_tipo_usuario_horario`
--
ALTER TABLE `agendamento_tipo_usuario_horario`
ADD CONSTRAINT `fk_agendamento_tipo_usuario_horario_agendamento_horario1` FOREIGN KEY (`id_agendamento_horario`) REFERENCES `agendamento_horario` (`id_agendamento_horario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_agendamento_tipo_usuario_horario_agendamento_login_tipo1` FOREIGN KEY (`id_agendamento_login_tipo`) REFERENCES `agendamento_login_tipo` (`id_agendamento_login_tipo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `agendamento_unidade_cidade`
--
ALTER TABLE `agendamento_unidade_cidade`
ADD CONSTRAINT `fk_agendamento_cidade_unidade_cidade1` FOREIGN KEY (`id_cidade`) REFERENCES `cidade` (`id_cidade`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_agendamento_cidade_unidade_unidade1` FOREIGN KEY (`id_unidade`) REFERENCES `unidade` (`id_unidade`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `arquivo`
--
ALTER TABLE `arquivo`
ADD CONSTRAINT `fk_arquivo_modulo1` FOREIGN KEY (`id_modulo`) REFERENCES `modulo` (`id_modulo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `city`
--
ALTER TABLE `city`
ADD CONSTRAINT `city_ibfk_1` FOREIGN KEY (`CountryCode`) REFERENCES `country` (`Code`);

--
-- Limitadores para a tabela `countrylanguage`
--
ALTER TABLE `countrylanguage`
ADD CONSTRAINT `countryLanguage_ibfk_1` FOREIGN KEY (`CountryCode`) REFERENCES `country` (`Code`);

--
-- Limitadores para a tabela `documento`
--
ALTER TABLE `documento`
ADD CONSTRAINT `fk_documento_carteira1` FOREIGN KEY (`id_carteira`) REFERENCES `carteira` (`id_carteira`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_documento_documento_tipo1` FOREIGN KEY (`id_documento_tipo`) REFERENCES `documento_tipo` (`id_documento_tipo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_documento_login1` FOREIGN KEY (`id_login`) REFERENCES `login` (`id_login`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_documento_processo1` FOREIGN KEY (`id_processo`) REFERENCES `processo` (`id_processo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_documento_servico1` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id_servico`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `documento_indexador`
--
ALTER TABLE `documento_indexador`
ADD CONSTRAINT `fk_documento_indexador_documento_tipo_campo1` FOREIGN KEY (`id_documento_indexador_formato`) REFERENCES `documento_indexador_formato` (`id_documento_indexador_formato`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `documento_tipo_indexadores`
--
ALTER TABLE `documento_tipo_indexadores`
ADD CONSTRAINT `fk_documento_tipo_indexadores_documento_indexador1` FOREIGN KEY (`id_documento_indexador`) REFERENCES `documento_indexador` (`id_documento_indexador`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_documento_tipo_indexadores_documento_tipo1` FOREIGN KEY (`id_documento_tipo`) REFERENCES `documento_tipo` (`id_documento_tipo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `documento_valor`
--
ALTER TABLE `documento_valor`
ADD CONSTRAINT `fk_documento_dados_documento1` FOREIGN KEY (`id_documento`) REFERENCES `documento` (`id_documento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_documento_valor_documento_indexador1` FOREIGN KEY (`id_documento_indexador`) REFERENCES `documento_indexador` (`id_documento_indexador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `evento`
--
ALTER TABLE `evento`
ADD CONSTRAINT `fk_evento_tipo_evento1` FOREIGN KEY (`id_tipo_evento`) REFERENCES `tipo_evento` (`id_tipo_evento`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `gru_processo`
--
ALTER TABLE `gru_processo`
ADD CONSTRAINT `fk_gru_processo_gru1` FOREIGN KEY (`id_gru`) REFERENCES `gru` (`id_gru`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_gru_processo_processo1` FOREIGN KEY (`id_processo`) REFERENCES `processo` (`id_processo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `gru_tentativa_fraude`
--
ALTER TABLE `gru_tentativa_fraude`
ADD CONSTRAINT `fk_gru_tentativa_fraude_gru1` FOREIGN KEY (`id_gru`) REFERENCES `gru` (`id_gru`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_gru_tentativa_fraude_interessado1` FOREIGN KEY (`id_interessado`) REFERENCES `interessado` (`id_interessado`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_gru_tentativa_fraude_login1` FOREIGN KEY (`id_login`) REFERENCES `login` (`id_login`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_gru_tentativa_fraude_procurador1` FOREIGN KEY (`id_procurador`) REFERENCES `procurador` (`id_procurador`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `interessado`
--
ALTER TABLE `interessado`
ADD CONSTRAINT `fk_interessado_cidade1` FOREIGN KEY (`id_cidade`) REFERENCES `cidade` (`id_cidade`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_interessado_tipo_interessado1` FOREIGN KEY (`sg_tipo_interessado`) REFERENCES `tipo_interessado` (`sg_tipo_interessado`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `login`
--
ALTER TABLE `login`
ADD CONSTRAINT `fk_login_login_perfil1` FOREIGN KEY (`id_login_perfil`) REFERENCES `login_perfil` (`id_login_perfil`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_login_posto_graduacao1` FOREIGN KEY (`id_posto_graduacao`) REFERENCES `posto_graduacao` (`id_posto_graduacao`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_login_unidade1` FOREIGN KEY (`id_unidade`) REFERENCES `unidade` (`id_unidade`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `login_carteira`
--
ALTER TABLE `login_carteira`
ADD CONSTRAINT `fk_acesso_carteira_carteira1` FOREIGN KEY (`id_carteira`) REFERENCES `carteira` (`id_carteira`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_acesso_carteira_login1` FOREIGN KEY (`id_login`) REFERENCES `login` (`id_login`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `login_unidade`
--
ALTER TABLE `login_unidade`
ADD CONSTRAINT `fk_login_unidade_login1` FOREIGN KEY (`id_login`) REFERENCES `login` (`id_login`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_login_unidade_unidade1` FOREIGN KEY (`id_unidade`) REFERENCES `unidade` (`id_unidade`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `modulo_permissao`
--
ALTER TABLE `modulo_permissao`
ADD CONSTRAINT `fk_modulo_permissao_login1` FOREIGN KEY (`id_login`) REFERENCES `login` (`id_login`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_modulo_permissao_modulo1` FOREIGN KEY (`id_modulo`) REFERENCES `modulo` (`id_modulo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `nota_informativa`
--
ALTER TABLE `nota_informativa`
ADD CONSTRAINT `fk_nota_informativa_login1` FOREIGN KEY (`id_login`) REFERENCES `login` (`id_login`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_nota_informativa_processo1` FOREIGN KEY (`id_processo`) REFERENCES `processo` (`id_processo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `parametro`
--
ALTER TABLE `parametro`
ADD CONSTRAINT `fk_parametros_modulo1` FOREIGN KEY (`id_modulo`) REFERENCES `modulo` (`id_modulo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_parametro_unidade1` FOREIGN KEY (`id_unidade`) REFERENCES `unidade` (`id_unidade`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `processo`
--
ALTER TABLE `processo`
ADD CONSTRAINT `fk_processo_carteira1` FOREIGN KEY (`id_carteira`) REFERENCES `carteira` (`id_carteira`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_processo_procurador1` FOREIGN KEY (`id_procurador`) REFERENCES `procurador` (`id_procurador`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_processo_unidade1` FOREIGN KEY (`id_unidade`) REFERENCES `unidade` (`id_unidade`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_protocolo_interessado1` FOREIGN KEY (`id_interessado`) REFERENCES `interessado` (`id_interessado`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `processo_andamento`
--
ALTER TABLE `processo_andamento`
ADD CONSTRAINT `fk_andamento_processo_processo1` FOREIGN KEY (`id_processo`) REFERENCES `processo` (`id_processo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_andamento_protocolo_login1` FOREIGN KEY (`id_login`) REFERENCES `login` (`id_login`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_processo_andamento_processo_status1` FOREIGN KEY (`id_processo_status`) REFERENCES `processo_status` (`id_processo_status`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `processo_servico`
--
ALTER TABLE `processo_servico`
ADD CONSTRAINT `fk_processo_servico_processo1` FOREIGN KEY (`id_processo`) REFERENCES `processo` (`id_processo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_processo_servico_servico1` FOREIGN KEY (`id_servico`) REFERENCES `servico` (`id_servico`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `servico`
--
ALTER TABLE `servico`
ADD CONSTRAINT `fk_servico_carteira1` FOREIGN KEY (`id_carteira`) REFERENCES `carteira` (`id_carteira`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `status_dependencia`
--
ALTER TABLE `status_dependencia`
ADD CONSTRAINT `fk_status_dependencia_processo_status1` FOREIGN KEY (`id_processo_status`) REFERENCES `processo_status` (`id_processo_status`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_status_dependencia_processo_status2` FOREIGN KEY (`id_processo_status_avanca`) REFERENCES `processo_status` (`id_processo_status`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `unidade`
--
ALTER TABLE `unidade`
ADD CONSTRAINT `fk_rede_sfpc_cidade1` FOREIGN KEY (`id_cidade`) REFERENCES `cidade` (`id_cidade`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `users`
--
ALTER TABLE `users`
ADD CONSTRAINT `users_id_agendamento_login_tipo_foreign` FOREIGN KEY (`id_agendamento_login_tipo`) REFERENCES `agendamento_login_tipo` (`id_agendamento_login_tipo`),
ADD CONSTRAINT `users_id_cidade_foreign` FOREIGN KEY (`id_cidade`) REFERENCES `cidade` (`id_cidade`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
