--
-- PostgreSQL database dump
--

-- Dumped from database version 13.1
-- Dumped by pg_dump version 13.1

-- Started on 2021-01-09 18:05:24

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 261 (class 1255 OID 16905)
-- Name: checkdispatcher(integer, integer, integer, boolean); Type: FUNCTION; Schema: public; Owner: staze
--

CREATE FUNCTION public.checkdispatcher(pk_dispatcher integer, pk_booker integer, pk_consignment integer, choice boolean) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
declare
  ShipCapacity real;
  TotalReserved integer;
  CurrReserved real;
  PK_Trip integer;
  PK_Request integer;
begin
  select "PK_Request"  into PK_Request
    from "Consignment" 
    where "PK_Consignment" = PK_Consignment;
    
  if Choice = true then



    /*check capacity*/
    select "CarryingCapacity" into ShipCapacity 
    from "ShipType" st
      left join "Ship" s on st."PK_ShipType" = s."PK_ShipType"
      left join "Trip" t on s."PK_Ship" = t."PK_Ship"
      left join "Request" r on t."PK_Trip" = r."PK_Trip"
      left join "Consignment" con on con."PK_Request" = r."PK_Request"
      where  con."PK_Consignment" = PK_Consignment;


    /*check accepted requests*/
    select "PK_Trip" into PK_Trip
      from "Request"
      where "PK_Request" = PK_Request;
    
    select sum("CargoAmount") into TotalReserved
    from "RequestLine" rl
      left join "Request" r on rl."PK_Request" = r."PK_Request"
      left join "Consignment" con on r."PK_Request" = con."PK_Request"
      where r."PK_Trip" = PK_Trip
            and r."RequestStatus" = 'Accepted';

    select sum("CargoAmount") into CurrReserved
    from "RequestLine" rl
      where rl."PK_Request" = PK_Request;

    if CurrReserved + TotalReserved > ShipCapacity then
      update "Request" 
        set "RequestStatus" = 'Refused'
        where "PK_Request" = PK_Request;

      delete from "Consignment"
        where "PK_Consignment" = PK_Consignment;
      return false;
    end if;

    update "Consignment"
      set "PK_Booker" = PK_Booker,
      "PK_Dispatcher" = PK_Dispatcher
      where "PK_Consignment" = PK_Consignment;

    update "Request" 
      set "RequestStatus" = 'Accepted'
      where "PK_Request" = PK_Request;

    return true;
  else
    update "Request" 
      set "RequestStatus" = 'Refused'
      where "PK_Request" = PK_Request;

    delete from "Consignment"
      where "PK_Consignment" = PK_Consignment;
    
    return false;
  end if;
end;
$$;


ALTER FUNCTION public.checkdispatcher(pk_dispatcher integer, pk_booker integer, pk_consignment integer, choice boolean) OWNER TO staze;

--
-- TOC entry 260 (class 1255 OID 16902)
-- Name: createconsignment(integer); Type: FUNCTION; Schema: public; Owner: staze
--

CREATE FUNCTION public.createconsignment(pk_request integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
declare
  TotalPrice money;
begin
  select sum("CargoAmount") * 
    (select "UnitPrice" from "Trip" t
      left join "Request" r on r."PK_Trip" = t."PK_Trip"
      where r."PK_Request" = PK_Request
    ) 
    into TotalPrice
    from "RequestLine" 
    where "PK_Request" = PK_Request;


  insert into "Consignment" ("BookingDate", "TotalPrice", "PK_Request") 
    values(now(), TotalPrice, PK_Request);

end;
$$;


ALTER FUNCTION public.createconsignment(pk_request integer) OWNER TO staze;

--
-- TOC entry 247 (class 1255 OID 16893)
-- Name: createcontract(date, date, money, integer, integer, integer, integer); Type: FUNCTION; Schema: public; Owner: staze
--

CREATE FUNCTION public.createcontract(dateconclusion date, dateexpiration date, salary money, pk_ship integer, pk_hremployee integer, pk_crewmember integer, pk_crewposition integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
begin
  insert into "Contract" ("PK_Contract", "DateConclusion", "DateExpiration", "Salary", "PK_Ship", 
                          "PK_HREmployee", "PK_CrewMember", "PK_CrewPosition")
  values (DEFAULT, DateConclusion, DateExpiration, Salary, PK_Ship, PK_HREmployee,
           PK_CrewMember, PK_CrewPosition);
end;
$$;


ALTER FUNCTION public.createcontract(dateconclusion date, dateexpiration date, salary money, pk_ship integer, pk_hremployee integer, pk_crewmember integer, pk_crewposition integer) OWNER TO staze;

--
-- TOC entry 246 (class 1255 OID 16898)
-- Name: createrequest(character varying, integer, integer, integer, integer, integer); Type: FUNCTION; Schema: public; Owner: staze
--

CREATE FUNCTION public.createrequest(requestnum character varying, pk_sender integer, pk_receiver integer, pk_trip integer, pk_portreceive integer, pk_portsend integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
begin
  insert into "Request"  ("RequestNum", "PK_Sender", "PK_Receiver", "PK_Trip", "PK_PortReceive", "PK_PortSend")
  values (RequestNum, PK_Sender, PK_Receiver, PK_Trip, PK_PortReceive, PK_PortSend);
end;
$$;


ALTER FUNCTION public.createrequest(requestnum character varying, pk_sender integer, pk_receiver integer, pk_trip integer, pk_portreceive integer, pk_portsend integer) OWNER TO staze;

--
-- TOC entry 248 (class 1255 OID 16896)
-- Name: createrequestline(character varying, integer, integer); Type: FUNCTION; Schema: public; Owner: staze
--

CREATE FUNCTION public.createrequestline(cargoname character varying, cargoamount integer, pk_request integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
begin
  insert into "RequestLine" ("CargoName", "CargoAmount", "PK_Request")
  values (CargoName, CargoAmount, PK_Request);
end;
$$;


ALTER FUNCTION public.createrequestline(cargoname character varying, cargoamount integer, pk_request integer) OWNER TO staze;

--
-- TOC entry 263 (class 1255 OID 16897)
-- Name: createtrip(money, money, timestamp without time zone, integer, integer[], character varying[], integer); Type: FUNCTION; Schema: public; Owner: staze
--

CREATE FUNCTION public.createtrip(costs money, unitprice money, datedeparture timestamp without time zone, pk_ship integer, ports integer[], intervals character varying[], amount integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
declare
  PK_Trip integer;
  summa timestamp;
  currInterval interval day to hour;
begin
	summa := DateDeparture;
	
  insert into "Trip" ("Cost", "UnitPrice", "DateDeparture", "PK_Ship")
    values(Costs, UnitPrice, DateDeparture, PK_Ship) 
    returning "PK_Trip" into PK_Trip;


  for i in 1..amount loop
	currInterval = cast(intervals[i] as interval day to hour);
	summa := summa + currInterval;
	
    if i != 1 and i != amount  then
      insert into "Route" ("TimeDuration", "IsFirst", "IsLast", "State", "PK_Trip", "PK_PortSend", "PK_PortReceive") 
      values (currInterval, false, false, false, PK_Trip, ports[i], ports[i + 1]);

    elsif i = 1 then
      insert into "Route" ("TimeDuration", "IsFirst", "IsLast", "State", "PK_Trip", "PK_PortSend", "PK_PortReceive") 
      values (currInterval, true, false, false, PK_Trip, ports[i], ports[i + 1]);

    else 
      insert into "Route" ("TimeDuration", "IsFirst", "IsLast", "State", "PK_Trip", "PK_PortSend", "PK_PortReceive") 
      values (currInterval, false, true, false, PK_Trip, ports[i], ports[i + 1]);
    end if;
  end loop;

  update "Trip"
    set "DateArrival" = summa
  where "PK_Trip" = PK_Trip;
end;
$$;


ALTER FUNCTION public.createtrip(costs money, unitprice money, datedeparture timestamp without time zone, pk_ship integer, ports integer[], intervals character varying[], amount integer) OWNER TO staze;

--
-- TOC entry 238 (class 1255 OID 16885)
-- Name: insertbooker(character varying, character varying, money); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.insertbooker(fullname character varying, personnelnum character varying, salary money) RETURNS void
    LANGUAGE plpgsql
    AS $$
begin
    insert into "Booker"("FullName", "PersonnelNum", "Salary")
    values (fullname, personnelNum, salary);
end;
$$;


ALTER FUNCTION public.insertbooker(fullname character varying, personnelnum character varying, salary money) OWNER TO postgres;

--
-- TOC entry 237 (class 1255 OID 16884)
-- Name: insertclient(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.insertclient(fullname character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
begin
    insert into "Client"("FullName") 
    values (fullname);
end;
$$;


ALTER FUNCTION public.insertclient(fullname character varying) OWNER TO postgres;

--
-- TOC entry 242 (class 1255 OID 16889)
-- Name: insertcrewmember(character varying); Type: FUNCTION; Schema: public; Owner: staze
--

CREATE FUNCTION public.insertcrewmember(fullname character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
begin
    insert into "CrewMember" ("PK_CrewMember", "FullName")
    values (DEFAULT, FullName);
end;
$$;


ALTER FUNCTION public.insertcrewmember(fullname character varying) OWNER TO staze;

--
-- TOC entry 243 (class 1255 OID 16890)
-- Name: insertcrewposition(character varying); Type: FUNCTION; Schema: public; Owner: staze
--

CREATE FUNCTION public.insertcrewposition(crewpositionname character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
begin
  insert into "CrewPosition" ("PK_CrewPosition", "CrewPositionName")
  values (DEFAULT, CrewPositionName);
end;
$$;


ALTER FUNCTION public.insertcrewposition(crewpositionname character varying) OWNER TO staze;

--
-- TOC entry 239 (class 1255 OID 16886)
-- Name: insertdispatcher(character varying, character varying, money); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.insertdispatcher(fullname character varying, personnelnum character varying, salary money) RETURNS void
    LANGUAGE plpgsql
    AS $$
begin
    insert into "Dispatcher"("FullName", "PersonnelNum", "Salary")
    values (fullname, personnelNum, salary);
end;
$$;


ALTER FUNCTION public.insertdispatcher(fullname character varying, personnelnum character varying, salary money) OWNER TO postgres;

--
-- TOC entry 244 (class 1255 OID 16891)
-- Name: inserthremployee(character varying, character varying, money); Type: FUNCTION; Schema: public; Owner: staze
--

CREATE FUNCTION public.inserthremployee(fullname character varying, personnelnum character varying, salary money) RETURNS void
    LANGUAGE plpgsql
    AS $$
begin
  insert into "HREmployee" ("PK_HREmployee", "FullName", "PersonnelNum", "Salary")
  values (DEFAULT, FullName, PersonnelNum, Salary);
end;
$$;


ALTER FUNCTION public.inserthremployee(fullname character varying, personnelnum character varying, salary money) OWNER TO staze;

--
-- TOC entry 240 (class 1255 OID 16887)
-- Name: insertport(character varying); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.insertport(portname character varying) RETURNS void
    LANGUAGE plpgsql
    AS $$
begin
    insert into "Port"("PortName")
    values (portName);
end;
$$;


ALTER FUNCTION public.insertport(portname character varying) OWNER TO postgres;

--
-- TOC entry 241 (class 1255 OID 16888)
-- Name: insertship(character varying, character varying, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.insertship(shipnumber character varying, shipname character varying, shiptype integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
begin
    insert into "Ship"("ShipNumber", "ShipName", "PK_ShipType")
    values (shipNumber, shipName, shipType);
end;
$$;


ALTER FUNCTION public.insertship(shipnumber character varying, shipname character varying, shiptype integer) OWNER TO postgres;

--
-- TOC entry 245 (class 1255 OID 16892)
-- Name: insertshiptype(character varying, real, integer, integer, integer, integer, integer, integer, integer); Type: FUNCTION; Schema: public; Owner: staze
--

CREATE FUNCTION public.insertshiptype(shiptypename character varying, carryingcapacity real, amountcaptains integer, amountcaptainhelpers integer, amountcooks integer, amountmechanics integer, amountelectricians integer, amountsailors integer, amountradiooperators integer) RETURNS void
    LANGUAGE plpgsql
    AS $$
begin
  insert into "ShipType" ("PK_ShipType", "ShipTypeName", "CarryingCapacity", "AmountCaptains", "AmountCaptainHelpers", 
                            "AmountCooks", "AmountMechanics", "AmountElectricians", "AmountSailors", "AmountRadioOperators")
  values (DEFAULT, ShipTypeName, CarryingCapacity, AmountCaptains, AmountCaptainHelpers, AmountCooks, AmountMechanics, AmountElectricians, AmountSailors, AmountRadioOperators);
end;
$$;


ALTER FUNCTION public.insertshiptype(shiptypename character varying, carryingcapacity real, amountcaptains integer, amountcaptainhelpers integer, amountcooks integer, amountmechanics integer, amountelectricians integer, amountsailors integer, amountradiooperators integer) OWNER TO staze;

--
-- TOC entry 262 (class 1255 OID 16906)
-- Name: refuseexpiredrequests(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.refuseexpiredrequests() RETURNS void
    LANGUAGE plpgsql
    AS $$
begin
    delete from "Consignment"
    where "PK_Request" = (select r."PK_Request" from "Request" r
                        left join "Trip" t on r."PK_Trip" = t."PK_Trip"
                        where r."RequestStatus" = 'verifying' and t."DateDeparture" - now() < cast('14 days' as interval));

    update "Request"
    set "RequestStatus" = 'Refused'
    where "PK_Request" = (select r."PK_Request" from "Request" r
                        left join "Trip" t on r."PK_Trip" = t."PK_Trip"
                        where r."RequestStatus" = 'verifying' and t."DateDeparture" - now() < cast('14 days' as interval));
end;
$$;


ALTER FUNCTION public.refuseexpiredrequests() OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 223 (class 1259 OID 16756)
-- Name: Booker; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."Booker" (
    "PK_Booker" integer NOT NULL,
    "FullName" character varying(100),
    "PersonnelNum" character varying(20),
    "Salary" money
)
WITH (autovacuum_enabled='true');


ALTER TABLE public."Booker" OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 16754)
-- Name: Booker_PK_Booker_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Booker_PK_Booker_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."Booker_PK_Booker_seq" OWNER TO postgres;

--
-- TOC entry 3241 (class 0 OID 0)
-- Dependencies: 222
-- Name: Booker_PK_Booker_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Booker_PK_Booker_seq" OWNED BY public."Booker"."PK_Booker";


--
-- TOC entry 201 (class 1259 OID 16660)
-- Name: Client; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."Client" (
    "PK_Client" integer NOT NULL,
    "FullName" character varying(100)
)
WITH (autovacuum_enabled='true');


ALTER TABLE public."Client" OWNER TO postgres;

--
-- TOC entry 200 (class 1259 OID 16658)
-- Name: Client_PK_Client_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Client_PK_Client_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."Client_PK_Client_seq" OWNER TO postgres;

--
-- TOC entry 3242 (class 0 OID 0)
-- Dependencies: 200
-- Name: Client_PK_Client_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Client_PK_Client_seq" OWNED BY public."Client"."PK_Client";


--
-- TOC entry 229 (class 1259 OID 16785)
-- Name: Consignment; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."Consignment" (
    "PK_Consignment" integer NOT NULL,
    "BookingDate" timestamp without time zone,
    "TotalPrice" money,
    "PK_Request" integer NOT NULL,
    "PK_Booker" integer,
    "PK_Dispatcher" integer
)
WITH (autovacuum_enabled='true');


ALTER TABLE public."Consignment" OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 16783)
-- Name: Consignment_PK_Consignment_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Consignment_PK_Consignment_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."Consignment_PK_Consignment_seq" OWNER TO postgres;

--
-- TOC entry 3243 (class 0 OID 0)
-- Dependencies: 228
-- Name: Consignment_PK_Consignment_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Consignment_PK_Consignment_seq" OWNED BY public."Consignment"."PK_Consignment";


--
-- TOC entry 215 (class 1259 OID 16717)
-- Name: Contract; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."Contract" (
    "PK_Contract" integer NOT NULL,
    "DateConclusion" date,
    "DateExpiration" date,
    "Salary" money,
    "PK_Ship" integer NOT NULL,
    "PK_HREmployee" integer NOT NULL,
    "PK_CrewMember" integer NOT NULL,
    "PK_CrewPosition" integer NOT NULL
)
WITH (autovacuum_enabled='true');


ALTER TABLE public."Contract" OWNER TO postgres;

--
-- TOC entry 214 (class 1259 OID 16715)
-- Name: Contract_PK_Contract_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Contract_PK_Contract_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."Contract_PK_Contract_seq" OWNER TO postgres;

--
-- TOC entry 3244 (class 0 OID 0)
-- Dependencies: 214
-- Name: Contract_PK_Contract_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Contract_PK_Contract_seq" OWNED BY public."Contract"."PK_Contract";


--
-- TOC entry 213 (class 1259 OID 16709)
-- Name: CrewMember; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."CrewMember" (
    "PK_CrewMember" integer NOT NULL,
    "FullName" character varying(100)
)
WITH (autovacuum_enabled='true');


ALTER TABLE public."CrewMember" OWNER TO postgres;

--
-- TOC entry 212 (class 1259 OID 16707)
-- Name: CrewMember_PK_CrewMember_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."CrewMember_PK_CrewMember_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."CrewMember_PK_CrewMember_seq" OWNER TO postgres;

--
-- TOC entry 3245 (class 0 OID 0)
-- Dependencies: 212
-- Name: CrewMember_PK_CrewMember_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."CrewMember_PK_CrewMember_seq" OWNED BY public."CrewMember"."PK_CrewMember";


--
-- TOC entry 211 (class 1259 OID 16701)
-- Name: CrewPosition; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."CrewPosition" (
    "PK_CrewPosition" integer NOT NULL,
    "CrewPositionName" character varying(100)
)
WITH (autovacuum_enabled='true');


ALTER TABLE public."CrewPosition" OWNER TO postgres;

--
-- TOC entry 210 (class 1259 OID 16699)
-- Name: CrewPosition_PK_CrewPosition_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."CrewPosition_PK_CrewPosition_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."CrewPosition_PK_CrewPosition_seq" OWNER TO postgres;

--
-- TOC entry 3246 (class 0 OID 0)
-- Dependencies: 210
-- Name: CrewPosition_PK_CrewPosition_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."CrewPosition_PK_CrewPosition_seq" OWNED BY public."CrewPosition"."PK_CrewPosition";


--
-- TOC entry 221 (class 1259 OID 16748)
-- Name: Dispatcher; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."Dispatcher" (
    "PK_Dispatcher" integer NOT NULL,
    "FullName" character varying(100),
    "PersonnelNum" character varying(20),
    "Salary" money
)
WITH (autovacuum_enabled='true');


ALTER TABLE public."Dispatcher" OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 16746)
-- Name: Dispatcher_PK_Dispatcher_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Dispatcher_PK_Dispatcher_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."Dispatcher_PK_Dispatcher_seq" OWNER TO postgres;

--
-- TOC entry 3247 (class 0 OID 0)
-- Dependencies: 220
-- Name: Dispatcher_PK_Dispatcher_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Dispatcher_PK_Dispatcher_seq" OWNED BY public."Dispatcher"."PK_Dispatcher";


--
-- TOC entry 205 (class 1259 OID 16676)
-- Name: HREmployee; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."HREmployee" (
    "PK_HREmployee" integer NOT NULL,
    "FullName" character varying(100),
    "PersonnelNum" character varying(20),
    "Salary" money
)
WITH (autovacuum_enabled='true');


ALTER TABLE public."HREmployee" OWNER TO postgres;

--
-- TOC entry 204 (class 1259 OID 16674)
-- Name: HREmployee_PK_HREmployee_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."HREmployee_PK_HREmployee_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."HREmployee_PK_HREmployee_seq" OWNER TO postgres;

--
-- TOC entry 3248 (class 0 OID 0)
-- Dependencies: 204
-- Name: HREmployee_PK_HREmployee_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."HREmployee_PK_HREmployee_seq" OWNED BY public."HREmployee"."PK_HREmployee";


--
-- TOC entry 203 (class 1259 OID 16668)
-- Name: Port; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."Port" (
    "PK_Port" integer NOT NULL,
    "PortName" character varying(100)
)
WITH (autovacuum_enabled='true');


ALTER TABLE public."Port" OWNER TO postgres;

--
-- TOC entry 202 (class 1259 OID 16666)
-- Name: Port_PK_Port_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Port_PK_Port_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."Port_PK_Port_seq" OWNER TO postgres;

--
-- TOC entry 3249 (class 0 OID 0)
-- Dependencies: 202
-- Name: Port_PK_Port_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Port_PK_Port_seq" OWNED BY public."Port"."PK_Port";


--
-- TOC entry 225 (class 1259 OID 16764)
-- Name: Request; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."Request" (
    "PK_Request" integer NOT NULL,
    "RequestNum" character varying(50),
    "PK_Sender" integer NOT NULL,
    "PK_Receiver" integer NOT NULL,
    "PK_Trip" integer NOT NULL,
    "PK_PortReceive" integer NOT NULL,
    "PK_PortSend" integer NOT NULL,
    "RequestStatus" character varying(50) DEFAULT 'verifying'::character varying
)
WITH (autovacuum_enabled='true');


ALTER TABLE public."Request" OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 16777)
-- Name: RequestLine; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."RequestLine" (
    "PK_RequestLine" integer NOT NULL,
    "CargoName" character varying(100),
    "CargoAmount" integer,
    "PK_Request" integer NOT NULL
)
WITH (autovacuum_enabled='true');


ALTER TABLE public."RequestLine" OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 16775)
-- Name: RequestLine_PK_RequestLine_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."RequestLine_PK_RequestLine_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."RequestLine_PK_RequestLine_seq" OWNER TO postgres;

--
-- TOC entry 3250 (class 0 OID 0)
-- Dependencies: 226
-- Name: RequestLine_PK_RequestLine_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."RequestLine_PK_RequestLine_seq" OWNED BY public."RequestLine"."PK_RequestLine";


--
-- TOC entry 224 (class 1259 OID 16762)
-- Name: Request_PK_Request_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Request_PK_Request_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."Request_PK_Request_seq" OWNER TO postgres;

--
-- TOC entry 3251 (class 0 OID 0)
-- Dependencies: 224
-- Name: Request_PK_Request_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Request_PK_Request_seq" OWNED BY public."Request"."PK_Request";


--
-- TOC entry 219 (class 1259 OID 16738)
-- Name: Route; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."Route" (
    "PK_Route" integer NOT NULL,
    "TimeDuration" interval day to hour,
    "IsFirst" boolean,
    "IsLast" boolean,
    "State" boolean,
    "PK_Trip" integer NOT NULL,
    "PK_PortReceive" integer NOT NULL,
    "PK_PortSend" integer NOT NULL
)
WITH (autovacuum_enabled='true');


ALTER TABLE public."Route" OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 16736)
-- Name: Route_PK_Route_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Route_PK_Route_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."Route_PK_Route_seq" OWNER TO postgres;

--
-- TOC entry 3252 (class 0 OID 0)
-- Dependencies: 218
-- Name: Route_PK_Route_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Route_PK_Route_seq" OWNED BY public."Route"."PK_Route";


--
-- TOC entry 209 (class 1259 OID 16692)
-- Name: Ship; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."Ship" (
    "PK_Ship" integer NOT NULL,
    "ShipNumber" character varying(30),
    "ShipName" character varying(100),
    "PK_ShipType" integer NOT NULL
)
WITH (autovacuum_enabled='true');


ALTER TABLE public."Ship" OWNER TO postgres;

--
-- TOC entry 207 (class 1259 OID 16684)
-- Name: ShipType; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."ShipType" (
    "PK_ShipType" integer NOT NULL,
    "ShipTypeName" character varying(100),
    "CarryingCapacity" real,
    "AmountCaptains" integer,
    "AmountCaptainHelpers" integer,
    "AmountCooks" integer,
    "AmountMechanics" integer,
    "AmountElectricians" integer,
    "AmountSailors" integer,
    "AmountRadioOperators" integer
)
WITH (autovacuum_enabled='true');


ALTER TABLE public."ShipType" OWNER TO postgres;

--
-- TOC entry 206 (class 1259 OID 16682)
-- Name: ShipType_PK_ShipType_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."ShipType_PK_ShipType_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."ShipType_PK_ShipType_seq" OWNER TO postgres;

--
-- TOC entry 3253 (class 0 OID 0)
-- Dependencies: 206
-- Name: ShipType_PK_ShipType_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."ShipType_PK_ShipType_seq" OWNED BY public."ShipType"."PK_ShipType";


--
-- TOC entry 208 (class 1259 OID 16690)
-- Name: Ship_PK_Ship_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Ship_PK_Ship_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."Ship_PK_Ship_seq" OWNER TO postgres;

--
-- TOC entry 3254 (class 0 OID 0)
-- Dependencies: 208
-- Name: Ship_PK_Ship_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Ship_PK_Ship_seq" OWNED BY public."Ship"."PK_Ship";


--
-- TOC entry 217 (class 1259 OID 16729)
-- Name: Trip; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."Trip" (
    "PK_Trip" integer NOT NULL,
    "Cost" money,
    "UnitPrice" money,
    "DateDeparture" timestamp without time zone,
    "DateArrival" timestamp without time zone,
    "PK_Ship" integer NOT NULL
)
WITH (autovacuum_enabled='true');


ALTER TABLE public."Trip" OWNER TO postgres;

--
-- TOC entry 216 (class 1259 OID 16727)
-- Name: Trip_PK_Trip_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."Trip_PK_Trip_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."Trip_PK_Trip_seq" OWNER TO postgres;

--
-- TOC entry 3255 (class 0 OID 0)
-- Dependencies: 216
-- Name: Trip_PK_Trip_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."Trip_PK_Trip_seq" OWNED BY public."Trip"."PK_Trip";


--
-- TOC entry 236 (class 1259 OID 16937)
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 16935)
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.failed_jobs_id_seq OWNER TO postgres;

--
-- TOC entry 3256 (class 0 OID 0)
-- Dependencies: 235
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 231 (class 1259 OID 16909)
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 16907)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.migrations_id_seq OWNER TO postgres;

--
-- TOC entry 3257 (class 0 OID 0)
-- Dependencies: 230
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 234 (class 1259 OID 16928)
-- Name: password_resets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_resets OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 16917)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 16915)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 3258 (class 0 OID 0)
-- Dependencies: 232
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 2986 (class 2604 OID 16759)
-- Name: Booker PK_Booker; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Booker" ALTER COLUMN "PK_Booker" SET DEFAULT nextval('public."Booker_PK_Booker_seq"'::regclass);


--
-- TOC entry 2975 (class 2604 OID 16663)
-- Name: Client PK_Client; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Client" ALTER COLUMN "PK_Client" SET DEFAULT nextval('public."Client_PK_Client_seq"'::regclass);


--
-- TOC entry 2990 (class 2604 OID 16788)
-- Name: Consignment PK_Consignment; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Consignment" ALTER COLUMN "PK_Consignment" SET DEFAULT nextval('public."Consignment_PK_Consignment_seq"'::regclass);


--
-- TOC entry 2982 (class 2604 OID 16720)
-- Name: Contract PK_Contract; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Contract" ALTER COLUMN "PK_Contract" SET DEFAULT nextval('public."Contract_PK_Contract_seq"'::regclass);


--
-- TOC entry 2981 (class 2604 OID 16712)
-- Name: CrewMember PK_CrewMember; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."CrewMember" ALTER COLUMN "PK_CrewMember" SET DEFAULT nextval('public."CrewMember_PK_CrewMember_seq"'::regclass);


--
-- TOC entry 2980 (class 2604 OID 16704)
-- Name: CrewPosition PK_CrewPosition; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."CrewPosition" ALTER COLUMN "PK_CrewPosition" SET DEFAULT nextval('public."CrewPosition_PK_CrewPosition_seq"'::regclass);


--
-- TOC entry 2985 (class 2604 OID 16751)
-- Name: Dispatcher PK_Dispatcher; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Dispatcher" ALTER COLUMN "PK_Dispatcher" SET DEFAULT nextval('public."Dispatcher_PK_Dispatcher_seq"'::regclass);


--
-- TOC entry 2977 (class 2604 OID 16679)
-- Name: HREmployee PK_HREmployee; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."HREmployee" ALTER COLUMN "PK_HREmployee" SET DEFAULT nextval('public."HREmployee_PK_HREmployee_seq"'::regclass);


--
-- TOC entry 2976 (class 2604 OID 16671)
-- Name: Port PK_Port; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Port" ALTER COLUMN "PK_Port" SET DEFAULT nextval('public."Port_PK_Port_seq"'::regclass);


--
-- TOC entry 2987 (class 2604 OID 16767)
-- Name: Request PK_Request; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Request" ALTER COLUMN "PK_Request" SET DEFAULT nextval('public."Request_PK_Request_seq"'::regclass);


--
-- TOC entry 2989 (class 2604 OID 16780)
-- Name: RequestLine PK_RequestLine; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."RequestLine" ALTER COLUMN "PK_RequestLine" SET DEFAULT nextval('public."RequestLine_PK_RequestLine_seq"'::regclass);


--
-- TOC entry 2984 (class 2604 OID 16741)
-- Name: Route PK_Route; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Route" ALTER COLUMN "PK_Route" SET DEFAULT nextval('public."Route_PK_Route_seq"'::regclass);


--
-- TOC entry 2979 (class 2604 OID 16695)
-- Name: Ship PK_Ship; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Ship" ALTER COLUMN "PK_Ship" SET DEFAULT nextval('public."Ship_PK_Ship_seq"'::regclass);


--
-- TOC entry 2978 (class 2604 OID 16687)
-- Name: ShipType PK_ShipType; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."ShipType" ALTER COLUMN "PK_ShipType" SET DEFAULT nextval('public."ShipType_PK_ShipType_seq"'::regclass);


--
-- TOC entry 2983 (class 2604 OID 16732)
-- Name: Trip PK_Trip; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Trip" ALTER COLUMN "PK_Trip" SET DEFAULT nextval('public."Trip_PK_Trip_seq"'::regclass);


--
-- TOC entry 2993 (class 2604 OID 16940)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 2991 (class 2604 OID 16912)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 2992 (class 2604 OID 16920)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 3222 (class 0 OID 16756)
-- Dependencies: 223
-- Data for Name: Booker; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."Booker" ("PK_Booker", "FullName", "PersonnelNum", "Salary") FROM stdin;
2	Стариков Е.К.	A-007	120 000,00 ?
\.


--
-- TOC entry 3200 (class 0 OID 16660)
-- Dependencies: 201
-- Data for Name: Client; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."Client" ("PK_Client", "FullName") FROM stdin;
1	Zhenya
2	Kutya
3	Max
4	Пиво и Рыба
5	ЗАО Закрытое Акционерное Общество
\.


--
-- TOC entry 3228 (class 0 OID 16785)
-- Dependencies: 229
-- Data for Name: Consignment; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."Consignment" ("PK_Consignment", "BookingDate", "TotalPrice", "PK_Request", "PK_Booker", "PK_Dispatcher") FROM stdin;
8	2020-12-29 16:52:30.034919	22 000 000,00 ?	5	2	2
12	2021-01-03 20:21:03.034903	4 015 000,00 ?	7	2	2
\.


--
-- TOC entry 3214 (class 0 OID 16717)
-- Dependencies: 215
-- Data for Name: Contract; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."Contract" ("PK_Contract", "DateConclusion", "DateExpiration", "Salary", "PK_Ship", "PK_HREmployee", "PK_CrewMember", "PK_CrewPosition") FROM stdin;
1	2020-08-13	2021-08-13	500 000,00 ?	1	1	1	1
2	2020-08-13	2021-08-13	300 000,00 ?	1	1	2	6
3	2020-08-13	2021-08-13	400 000,00 ?	1	1	3	6
4	2020-08-13	2021-08-13	200 000,00 ?	1	1	4	7
5	2020-08-13	2022-08-13	1 000 000,00 ?	2	1	5	1
6	2020-08-13	2021-08-13	600 000,00 ?	2	1	6	6
7	2020-08-13	2021-08-13	500 000,00 ?	2	1	7	6
8	2020-08-13	2021-08-13	600 000,00 ?	2	1	8	3
\.


--
-- TOC entry 3212 (class 0 OID 16709)
-- Dependencies: 213
-- Data for Name: CrewMember; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."CrewMember" ("PK_CrewMember", "FullName") FROM stdin;
1	Федоров В.И.
2	Павлов Е.К.
3	Член Экипажа Е.П.
4	Андреев А.Д.
5	Степнов Е.В.
6	Несваренов Д.Д.
7	Куборкин А.С.
8	Попов П.Д.
9	Соболев А.А.
10	Евгеньев Е.Е.
11	Петров П.П.
\.


--
-- TOC entry 3210 (class 0 OID 16701)
-- Dependencies: 211
-- Data for Name: CrewPosition; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."CrewPosition" ("PK_CrewPosition", "CrewPositionName") FROM stdin;
1	Капитан
2	Помощник капитана
3	Повар
4	Механик
5	Электрик
6	Матрос
7	Радиооператор
\.


--
-- TOC entry 3220 (class 0 OID 16748)
-- Dependencies: 221
-- Data for Name: Dispatcher; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."Dispatcher" ("PK_Dispatcher", "FullName", "PersonnelNum", "Salary") FROM stdin;
2	Петров В.А.	56-V	100 000,00 ?
\.


--
-- TOC entry 3204 (class 0 OID 16676)
-- Dependencies: 205
-- Data for Name: HREmployee; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."HREmployee" ("PK_HREmployee", "FullName", "PersonnelNum", "Salary") FROM stdin;
1	Иванов И.И	12-A	80 000,00 ?
\.


--
-- TOC entry 3202 (class 0 OID 16668)
-- Dependencies: 203
-- Data for Name: Port; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."Port" ("PK_Port", "PortName") FROM stdin;
1	Барнаул
2	Москва
3	Заринск
4	Нью-Йорк
5	Владивосток
6	Новоссибирск
7	Токио
\.


--
-- TOC entry 3224 (class 0 OID 16764)
-- Dependencies: 225
-- Data for Name: Request; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."Request" ("PK_Request", "RequestNum", "PK_Sender", "PK_Receiver", "PK_Trip", "PK_PortReceive", "PK_PortSend", "RequestStatus") FROM stdin;
5	24	1	2	2	1	4	Accepted
4	25	1	4	2	1	3	verifying
6	23	2	4	3	1	2	Refused
7	78	4	5	7	4	1	Accepted
\.


--
-- TOC entry 3226 (class 0 OID 16777)
-- Dependencies: 227
-- Data for Name: RequestLine; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."RequestLine" ("PK_RequestLine", "CargoName", "CargoAmount", "PK_Request") FROM stdin;
9	Шампунь	2000	5
10	Мыло	1000	5
5	Яблоки	1000	4
6	Груши	4000	4
7	Семечки	6000	4
8	Бумага	4000	5
11	Бочка	255	7
12	Кочка	110	7
13	Игуана	350	6
\.


--
-- TOC entry 3218 (class 0 OID 16738)
-- Dependencies: 219
-- Data for Name: Route; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."Route" ("PK_Route", "TimeDuration", "IsFirst", "IsLast", "State", "PK_Trip", "PK_PortReceive", "PK_PortSend") FROM stdin;
2	2 days 01:00:00	t	f	f	2	1	4
3	1 day 01:00:00	f	f	f	2	2	1
4	3 days 13:00:00	f	f	f	2	3	2
5	4 days 04:00:00	f	f	f	2	6	3
16	3 days 05:00:00	t	f	f	7	4	1
6	1 day	f	t	f	2	5	6
17	1 day 02:00:00	f	t	f	7	6	4
\.


--
-- TOC entry 3208 (class 0 OID 16692)
-- Dependencies: 209
-- Data for Name: Ship; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."Ship" ("PK_Ship", "ShipNumber", "ShipName", "PK_ShipType") FROM stdin;
1	ShipNumber	ShipName	1
2	007	Карамзин	2
3	0123	testShip	1
\.


--
-- TOC entry 3206 (class 0 OID 16684)
-- Dependencies: 207
-- Data for Name: ShipType; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."ShipType" ("PK_ShipType", "ShipTypeName", "CarryingCapacity", "AmountCaptains", "AmountCaptainHelpers", "AmountCooks", "AmountMechanics", "AmountElectricians", "AmountSailors", "AmountRadioOperators") FROM stdin;
1	Баржа	5550	1	1	1	1	5	2	1
2	Авианосец	12000	20	1	1	1	1	1	1
3	Катер	500	1	0	1	1	1	1	1
\.


--
-- TOC entry 3216 (class 0 OID 16729)
-- Dependencies: 217
-- Data for Name: Trip; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."Trip" ("PK_Trip", "Cost", "UnitPrice", "DateDeparture", "DateArrival", "PK_Ship") FROM stdin;
2	500 000,00 ?	100 000,00 ?	2021-10-12 00:00:00	2021-10-23 19:00:00	2
3	10 000,00 ?	10 000,00 ?	2020-12-12 00:00:00	2020-12-30 00:00:00	1
7	1 500 000,00 ?	11 000,00 ?	2021-04-01 00:00:00	2021-04-05 07:00:00	2
\.


--
-- TOC entry 3235 (class 0 OID 16937)
-- Dependencies: 236
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- TOC entry 3230 (class 0 OID 16909)
-- Dependencies: 231
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2014_10_12_000000_create_users_table	1
2	2014_10_12_100000_create_password_resets_table	1
3	2019_08_19_000000_create_failed_jobs_table	1
\.


--
-- TOC entry 3233 (class 0 OID 16928)
-- Dependencies: 234
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_resets (email, token, created_at) FROM stdin;
\.


--
-- TOC entry 3232 (class 0 OID 16917)
-- Dependencies: 233
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) FROM stdin;
1	a1pha1337	ichigo432@gmail.com	\N	$2y$10$ObkQwCRfIzt1d5fG8.ymEuxVbnrwFQekdA7IVV9YsT23KetLGpY1W	\N	2021-01-09 10:27:39	2021-01-09 10:27:39
2	Maximka	artemloh@mail.ru	\N	$2y$10$DfPOtLbv5zdBYbAH7uDKcerbhDmEyc18ECrWBGiQr1W.zjpgH3CI2	\N	2021-01-09 10:53:22	2021-01-09 10:53:22
\.


--
-- TOC entry 3259 (class 0 OID 0)
-- Dependencies: 222
-- Name: Booker_PK_Booker_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Booker_PK_Booker_seq"', 2, true);


--
-- TOC entry 3260 (class 0 OID 0)
-- Dependencies: 200
-- Name: Client_PK_Client_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Client_PK_Client_seq"', 5, true);


--
-- TOC entry 3261 (class 0 OID 0)
-- Dependencies: 228
-- Name: Consignment_PK_Consignment_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Consignment_PK_Consignment_seq"', 14, true);


--
-- TOC entry 3262 (class 0 OID 0)
-- Dependencies: 214
-- Name: Contract_PK_Contract_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Contract_PK_Contract_seq"', 8, true);


--
-- TOC entry 3263 (class 0 OID 0)
-- Dependencies: 212
-- Name: CrewMember_PK_CrewMember_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."CrewMember_PK_CrewMember_seq"', 11, true);


--
-- TOC entry 3264 (class 0 OID 0)
-- Dependencies: 210
-- Name: CrewPosition_PK_CrewPosition_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."CrewPosition_PK_CrewPosition_seq"', 7, true);


--
-- TOC entry 3265 (class 0 OID 0)
-- Dependencies: 220
-- Name: Dispatcher_PK_Dispatcher_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Dispatcher_PK_Dispatcher_seq"', 2, true);


--
-- TOC entry 3266 (class 0 OID 0)
-- Dependencies: 204
-- Name: HREmployee_PK_HREmployee_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."HREmployee_PK_HREmployee_seq"', 1, true);


--
-- TOC entry 3267 (class 0 OID 0)
-- Dependencies: 202
-- Name: Port_PK_Port_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Port_PK_Port_seq"', 7, true);


--
-- TOC entry 3268 (class 0 OID 0)
-- Dependencies: 226
-- Name: RequestLine_PK_RequestLine_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."RequestLine_PK_RequestLine_seq"', 13, true);


--
-- TOC entry 3269 (class 0 OID 0)
-- Dependencies: 224
-- Name: Request_PK_Request_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Request_PK_Request_seq"', 7, true);


--
-- TOC entry 3270 (class 0 OID 0)
-- Dependencies: 218
-- Name: Route_PK_Route_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Route_PK_Route_seq"', 17, true);


--
-- TOC entry 3271 (class 0 OID 0)
-- Dependencies: 206
-- Name: ShipType_PK_ShipType_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."ShipType_PK_ShipType_seq"', 3, true);


--
-- TOC entry 3272 (class 0 OID 0)
-- Dependencies: 208
-- Name: Ship_PK_Ship_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Ship_PK_Ship_seq"', 3, true);


--
-- TOC entry 3273 (class 0 OID 0)
-- Dependencies: 216
-- Name: Trip_PK_Trip_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."Trip_PK_Trip_seq"', 7, true);


--
-- TOC entry 3274 (class 0 OID 0)
-- Dependencies: 235
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 3275 (class 0 OID 0)
-- Dependencies: 230
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 3, true);


--
-- TOC entry 3276 (class 0 OID 0)
-- Dependencies: 232
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 2, true);


--
-- TOC entry 3026 (class 2606 OID 16761)
-- Name: Booker PK_Booker; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Booker"
    ADD CONSTRAINT "PK_Booker" PRIMARY KEY ("PK_Booker");


--
-- TOC entry 2996 (class 2606 OID 16665)
-- Name: Client PK_Client; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Client"
    ADD CONSTRAINT "PK_Client" PRIMARY KEY ("PK_Client");


--
-- TOC entry 3039 (class 2606 OID 16792)
-- Name: Consignment PK_Consignment; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Consignment"
    ADD CONSTRAINT "PK_Consignment" PRIMARY KEY ("PK_Consignment", "PK_Request");


--
-- TOC entry 3015 (class 2606 OID 16726)
-- Name: Contract PK_Contract; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Contract"
    ADD CONSTRAINT "PK_Contract" PRIMARY KEY ("PK_Contract");


--
-- TOC entry 3009 (class 2606 OID 16714)
-- Name: CrewMember PK_CrewMember; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."CrewMember"
    ADD CONSTRAINT "PK_CrewMember" PRIMARY KEY ("PK_CrewMember");


--
-- TOC entry 3007 (class 2606 OID 16706)
-- Name: CrewPosition PK_CrewPosition; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."CrewPosition"
    ADD CONSTRAINT "PK_CrewPosition" PRIMARY KEY ("PK_CrewPosition");


--
-- TOC entry 3024 (class 2606 OID 16753)
-- Name: Dispatcher PK_Dispatcher; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Dispatcher"
    ADD CONSTRAINT "PK_Dispatcher" PRIMARY KEY ("PK_Dispatcher");


--
-- TOC entry 3000 (class 2606 OID 16681)
-- Name: HREmployee PK_HREmployee; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."HREmployee"
    ADD CONSTRAINT "PK_HREmployee" PRIMARY KEY ("PK_HREmployee");


--
-- TOC entry 2998 (class 2606 OID 16673)
-- Name: Port PK_Port; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Port"
    ADD CONSTRAINT "PK_Port" PRIMARY KEY ("PK_Port");


--
-- TOC entry 3033 (class 2606 OID 16774)
-- Name: Request PK_Request; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Request"
    ADD CONSTRAINT "PK_Request" PRIMARY KEY ("PK_Request");


--
-- TOC entry 3035 (class 2606 OID 16782)
-- Name: RequestLine PK_RequestLine; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."RequestLine"
    ADD CONSTRAINT "PK_RequestLine" PRIMARY KEY ("PK_RequestLine", "PK_Request");


--
-- TOC entry 3022 (class 2606 OID 16745)
-- Name: Route PK_Route; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Route"
    ADD CONSTRAINT "PK_Route" PRIMARY KEY ("PK_Route", "PK_Trip");


--
-- TOC entry 3005 (class 2606 OID 16698)
-- Name: Ship PK_Ship; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Ship"
    ADD CONSTRAINT "PK_Ship" PRIMARY KEY ("PK_Ship");


--
-- TOC entry 3002 (class 2606 OID 16689)
-- Name: ShipType PK_ShipType; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."ShipType"
    ADD CONSTRAINT "PK_ShipType" PRIMARY KEY ("PK_ShipType");


--
-- TOC entry 3018 (class 2606 OID 16735)
-- Name: Trip PK_Trip; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Trip"
    ADD CONSTRAINT "PK_Trip" PRIMARY KEY ("PK_Trip");


--
-- TOC entry 3048 (class 2606 OID 16946)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 3050 (class 2606 OID 16948)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 3041 (class 2606 OID 16914)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 3043 (class 2606 OID 16927)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 3045 (class 2606 OID 16925)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 3036 (class 1259 OID 16789)
-- Name: IX_Relationship11; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "IX_Relationship11" ON public."Consignment" USING btree ("PK_Booker");


--
-- TOC entry 3037 (class 1259 OID 16790)
-- Name: IX_Relationship12; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "IX_Relationship12" ON public."Consignment" USING btree ("PK_Dispatcher");


--
-- TOC entry 3019 (class 1259 OID 16742)
-- Name: IX_Relationship14; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "IX_Relationship14" ON public."Route" USING btree ("PK_PortReceive");


--
-- TOC entry 3020 (class 1259 OID 16743)
-- Name: IX_Relationship15; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "IX_Relationship15" ON public."Route" USING btree ("PK_PortSend");


--
-- TOC entry 3016 (class 1259 OID 16733)
-- Name: IX_Relationship16; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "IX_Relationship16" ON public."Trip" USING btree ("PK_Ship");


--
-- TOC entry 3003 (class 1259 OID 16696)
-- Name: IX_Relationship17; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "IX_Relationship17" ON public."Ship" USING btree ("PK_ShipType");


--
-- TOC entry 3010 (class 1259 OID 16721)
-- Name: IX_Relationship18; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "IX_Relationship18" ON public."Contract" USING btree ("PK_Ship");


--
-- TOC entry 3011 (class 1259 OID 16722)
-- Name: IX_Relationship19; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "IX_Relationship19" ON public."Contract" USING btree ("PK_HREmployee");


--
-- TOC entry 3012 (class 1259 OID 16723)
-- Name: IX_Relationship20; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "IX_Relationship20" ON public."Contract" USING btree ("PK_CrewMember");


--
-- TOC entry 3013 (class 1259 OID 16724)
-- Name: IX_Relationship21; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "IX_Relationship21" ON public."Contract" USING btree ("PK_CrewPosition");


--
-- TOC entry 3027 (class 1259 OID 16768)
-- Name: IX_Relationship4; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "IX_Relationship4" ON public."Request" USING btree ("PK_Sender");


--
-- TOC entry 3028 (class 1259 OID 16769)
-- Name: IX_Relationship5; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "IX_Relationship5" ON public."Request" USING btree ("PK_Receiver");


--
-- TOC entry 3029 (class 1259 OID 16770)
-- Name: IX_Relationship6; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "IX_Relationship6" ON public."Request" USING btree ("PK_Trip");


--
-- TOC entry 3030 (class 1259 OID 16771)
-- Name: IX_Relationship7; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "IX_Relationship7" ON public."Request" USING btree ("PK_PortReceive");


--
-- TOC entry 3031 (class 1259 OID 16772)
-- Name: IX_Relationship9; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX "IX_Relationship9" ON public."Request" USING btree ("PK_PortSend");


--
-- TOC entry 3046 (class 1259 OID 16934)
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);


--
-- TOC entry 3066 (class 2606 OID 16818)
-- Name: Consignment Relationship10; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Consignment"
    ADD CONSTRAINT "Relationship10" FOREIGN KEY ("PK_Request") REFERENCES public."Request"("PK_Request") ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- TOC entry 3067 (class 2606 OID 16823)
-- Name: Consignment Relationship11; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Consignment"
    ADD CONSTRAINT "Relationship11" FOREIGN KEY ("PK_Booker") REFERENCES public."Booker"("PK_Booker") ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3068 (class 2606 OID 16828)
-- Name: Consignment Relationship12; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Consignment"
    ADD CONSTRAINT "Relationship12" FOREIGN KEY ("PK_Dispatcher") REFERENCES public."Dispatcher"("PK_Dispatcher") ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3057 (class 2606 OID 16833)
-- Name: Route Relationship13; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Route"
    ADD CONSTRAINT "Relationship13" FOREIGN KEY ("PK_Trip") REFERENCES public."Trip"("PK_Trip") ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- TOC entry 3058 (class 2606 OID 16838)
-- Name: Route Relationship14; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Route"
    ADD CONSTRAINT "Relationship14" FOREIGN KEY ("PK_PortReceive") REFERENCES public."Port"("PK_Port") ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3059 (class 2606 OID 16843)
-- Name: Route Relationship15; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Route"
    ADD CONSTRAINT "Relationship15" FOREIGN KEY ("PK_PortSend") REFERENCES public."Port"("PK_Port") ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3056 (class 2606 OID 16848)
-- Name: Trip Relationship16; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Trip"
    ADD CONSTRAINT "Relationship16" FOREIGN KEY ("PK_Ship") REFERENCES public."Ship"("PK_Ship") ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3051 (class 2606 OID 16853)
-- Name: Ship Relationship17; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Ship"
    ADD CONSTRAINT "Relationship17" FOREIGN KEY ("PK_ShipType") REFERENCES public."ShipType"("PK_ShipType") ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3052 (class 2606 OID 16858)
-- Name: Contract Relationship18; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Contract"
    ADD CONSTRAINT "Relationship18" FOREIGN KEY ("PK_Ship") REFERENCES public."Ship"("PK_Ship") ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3053 (class 2606 OID 16863)
-- Name: Contract Relationship19; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Contract"
    ADD CONSTRAINT "Relationship19" FOREIGN KEY ("PK_HREmployee") REFERENCES public."HREmployee"("PK_HREmployee") ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3054 (class 2606 OID 16868)
-- Name: Contract Relationship20; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Contract"
    ADD CONSTRAINT "Relationship20" FOREIGN KEY ("PK_CrewMember") REFERENCES public."CrewMember"("PK_CrewMember") ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3055 (class 2606 OID 16873)
-- Name: Contract Relationship21; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Contract"
    ADD CONSTRAINT "Relationship21" FOREIGN KEY ("PK_CrewPosition") REFERENCES public."CrewPosition"("PK_CrewPosition") ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3065 (class 2606 OID 16878)
-- Name: RequestLine Relationship22; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."RequestLine"
    ADD CONSTRAINT "Relationship22" FOREIGN KEY ("PK_Request") REFERENCES public."Request"("PK_Request") ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- TOC entry 3060 (class 2606 OID 16793)
-- Name: Request Relationship4; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Request"
    ADD CONSTRAINT "Relationship4" FOREIGN KEY ("PK_Sender") REFERENCES public."Client"("PK_Client") ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3061 (class 2606 OID 16798)
-- Name: Request Relationship5; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Request"
    ADD CONSTRAINT "Relationship5" FOREIGN KEY ("PK_Receiver") REFERENCES public."Client"("PK_Client") ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3062 (class 2606 OID 16803)
-- Name: Request Relationship6; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Request"
    ADD CONSTRAINT "Relationship6" FOREIGN KEY ("PK_Trip") REFERENCES public."Trip"("PK_Trip") ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3063 (class 2606 OID 16808)
-- Name: Request Relationship7; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Request"
    ADD CONSTRAINT "Relationship7" FOREIGN KEY ("PK_PortReceive") REFERENCES public."Port"("PK_Port") ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3064 (class 2606 OID 16813)
-- Name: Request Relationship9; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."Request"
    ADD CONSTRAINT "Relationship9" FOREIGN KEY ("PK_PortSend") REFERENCES public."Port"("PK_Port") ON UPDATE RESTRICT ON DELETE RESTRICT;


-- Completed on 2021-01-09 18:05:24

--
-- PostgreSQL database dump complete
--

