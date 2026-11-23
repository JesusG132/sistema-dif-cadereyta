-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 23-11-2026 a las 22:50:35
-- Versión del servidor: 8.0.46
-- Versión de PHP: 8.3.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_dif`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alimentos_calientes`
--

CREATE TABLE `alimentos_calientes` (
  `id_entrega` int NOT NULL,
  `folio_apoyo` varchar(50) NOT NULL,
  `comedor_escuela` varchar(150) NOT NULL,
  `localidad` varchar(100) NOT NULL,
  `tipo_programa` varchar(100) NOT NULL,
  `raciones_diarias` int NOT NULL,
  `fecha_entrega` date NOT NULL,
  `observaciones` text,
  `usuario_atiende` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `alimentos_calientes`
--

INSERT INTO `alimentos_calientes` (`id_entrega`, `folio_apoyo`, `comedor_escuela`, `localidad`, `tipo_programa`, `raciones_diarias`, `fecha_entrega`, `observaciones`, `usuario_atiende`) VALUES
(1, 'DES-E-2026-3001', 'Primaria Benito Juárez', 'Boyé', 'Desayuno Escolar Caliente', 85, '2026-10-01', 'Abastecimiento de granos y vegetales', 'Admin'),
(2, 'DES-E-2026-3002', 'Comedor Comunitario Boyé Central', 'Boyé', 'Comedor Comunitario Abierto', 120, '2026-10-01', 'Atención a adultos mayores de la zona', 'Admin'),
(3, 'DES-E-2026-3003', 'Jardín de Niños Miguel Hidalgo', 'Vizarrón de Montes', 'Desayuno Escolar Caliente', 45, '2026-10-02', 'Entrega de leche y huevo en polvo', 'Admin'),
(4, 'DES-E-2026-3004', 'Primaria Niños Héroes', 'El Doctor', 'Desayuno Escolar Caliente', 60, '2026-10-02', 'Comedor equipado en sierra', 'Admin'),
(5, 'DES-E-2026-3005', 'Comedor Comunitario Tzibanzá', 'Tzibanzá', 'Comedor Comunitario Abierto', 90, '2026-10-03', 'Población vulnerable e hidalguenses', 'Admin'),
(6, 'DES-E-2026-3006', 'Telesecundaria Vicente Guerrero', 'La Campana', 'Insumos para Cocina Escolar', 75, '2026-10-03', 'Dotación mensual completa', 'Admin'),
(7, 'DES-E-2026-3007', 'Primaria Josefa Ortiz de Domínguez', 'Macavaca', 'Desayuno Escolar Caliente', 50, '2026-10-04', 'Supervisión de estufa e insumos', 'Admin'),
(8, 'DES-E-2026-3008', 'Jardín de Niños Sor Juana Inés', 'Higuerillas', 'Desayuno Escolar Caliente', 40, '2026-10-04', 'Cocina escolar activa', 'Admin'),
(9, 'DES-E-2026-3009', 'Comedor DIF Cadereyta Centro', 'Cadereyta Centro', 'Comedor Comunitario Abierto', 150, '2026-10-05', 'Raciones diarias servidas al público', 'Admin'),
(10, 'DES-E-2026-3010', 'Primaria 16 de Septiembre', 'San Gaspar', 'Desayuno Escolar Caliente', 65, '2026-10-05', 'Verduras frescas y arroz integrados', 'Admin'),
(11, 'DES-E-2026-3011', 'Telesecundaria Lázaro Cárdenas', 'Boyé', 'Insumos para Cocina Escolar', 80, '2026-10-06', 'Revisión de almacén comités', 'Admin'),
(12, 'DES-E-2026-3012', 'Comedor Comunitario Vizarrón', 'Vizarrón de Montes', 'Comedor Comunitario Abierto', 110, '2026-10-06', 'Raciones calientes mediodía', 'Admin'),
(13, 'DES-E-2026-3013', 'Jardín de Niños Capullito', 'El Doctor', 'Desayuno Escolar Caliente', 35, '2026-10-07', 'Entrega bimestral insumos', 'Admin'),
(14, 'DES-E-2026-3014', 'Primaria Guadalupe Victoria', 'Tzibanzá', 'Desayuno Escolar Caliente', 55, '2026-10-07', 'Suministro de gas e ingredientes', 'Admin'),
(15, 'DES-E-2026-3015', 'Comedor Comunitario La Campana', 'La Campana', 'Comedor Comunitario Abierto', 80, '2026-10-08', 'Atención directa comunitaria', 'Admin'),
(16, 'DES-E-2026-3016', 'Telesecundaria Amado Nervo', 'Macavaca', 'Insumos para Cocina Escolar', 70, '2026-10-08', 'Control mensual de inventarios', 'Admin'),
(17, 'DES-E-2026-3017', 'Primaria Revolución', 'Higuerillas', 'Desayuno Escolar Caliente', 60, '2026-10-09', 'Insumos calientes completos', 'Admin'),
(18, 'DES-E-2026-3018', 'Jardín de Niños Francisco I. Madero', 'San Gaspar', 'Desayuno Escolar Caliente', 42, '2026-10-09', 'Lácteos e ingredientes secos', 'Admin'),
(19, 'DES-E-2026-3019', 'Comedor Escolar Centro Escolar', 'Cadereyta Centro', 'Desayuno Escolar Caliente', 130, '2026-10-10', 'Población infantil urbana', 'Admin'),
(20, 'DES-E-2026-3020', 'Primaria Melchor Ocampo', 'Boyé', 'Desayuno Escolar Caliente', 90, '2026-10-10', 'Desayuno caliente a las 8:00 AM', 'Admin'),
(21, 'DES-E-2026-3021', 'Comedor Comunitario El Doctor', 'El Doctor', 'Comedor Comunitario Abierto', 95, '2026-10-11', 'Comida caliente para adultos mayores', 'Admin'),
(22, 'DES-E-2026-3022', 'Telesecundaria Justo Sierra', 'Vizarrón de Montes', 'Insumos para Cocina Escolar', 88, '2026-10-11', 'Insumos secos y conservas', 'Admin'),
(23, 'DES-E-2026-3023', 'Primaria Emiliano Zapata', 'Tzibanzá', 'Desayuno Escolar Caliente', 48, '2026-10-12', 'Sostenimiento escolar', 'Admin'),
(24, 'DES-E-2026-3024', 'Jardín de Niños Andrés Quintana Roo', 'La Campana', 'Desayuno Escolar Caliente', 38, '2026-10-12', 'Atención presustancial', 'Admin'),
(25, 'DES-E-2026-3025', 'Comedor Comunitario Higuerillas', 'Higuerillas', 'Comedor Comunitario Abierto', 100, '2026-10-13', 'Dotación de víveres calientes', 'Admin'),
(26, 'DES-E-2026-3026', 'Primaria Alfonso Reyes', 'Macavaca', 'Desayuno Escolar Caliente', 52, '2026-10-13', 'Menú balanceado supervisado', 'Admin'),
(27, 'DES-E-2026-3027', 'Telesecundaria Rosario Castellanos', 'San Gaspar', 'Insumos para Cocina Escolar', 64, '2026-10-14', 'Insumos recibidos por el comité', 'Admin'),
(28, 'DES-E-2026-3028', 'Comedor Estudiantil Universitario', 'Cadereyta Centro', 'Comedor Comunitario Abierto', 140, '2026-10-14', 'Raciones de comida corrida', 'Admin'),
(29, 'DES-E-2026-3029', 'Jardín de Niños Gabriela Mistral', 'Boyé', 'Desayuno Escolar Caliente', 46, '2026-10-15', 'Programa de nutrición preescolar', 'Admin'),
(30, 'DES-E-2026-3030', 'Primaria Guillermo Prieto', 'Vizarrón de Montes', 'Desayuno Escolar Caliente', 78, '2026-10-15', 'Cierre de entregas de octubre', 'Admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alimentos_frios`
--

CREATE TABLE `alimentos_frios` (
  `id_entrega` int NOT NULL,
  `folio_apoyo` varchar(50) NOT NULL,
  `beneficiario` varchar(150) NOT NULL,
  `localidad` varchar(100) NOT NULL,
  `tipo_apoyo` varchar(100) NOT NULL,
  `cantidad_paquetes` int NOT NULL,
  `fecha_entrega` date NOT NULL,
  `observaciones` text,
  `usuario_atiende` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `alimentos_frios`
--

INSERT INTO `alimentos_frios` (`id_entrega`, `folio_apoyo`, `beneficiario`, `localidad`, `tipo_apoyo`, `cantidad_paquetes`, `fecha_entrega`, `observaciones`, `usuario_atiende`) VALUES
(1, 'ALIM-2026-1001', 'María Dolores Sánchez', 'Boyé', 'Despensa Básica Familiar', 1, '2026-10-01', 'Entrega puntual mes de octubre', 'Admin'),
(2, 'ALIM-2026-1002', 'José Luis Reséndiz', 'Vizarrón de Montes', 'Dotación Adulto Mayor', 2, '2026-10-01', 'Apoyo bimestral tercera edad', 'Admin'),
(3, 'ALIM-2026-1003', 'Ana Karen Trejo', 'El Doctor', 'Paquete Desayunos Escolares Fríos', 1, '2026-10-02', 'Primaria Benito Juárez', 'Admin'),
(4, 'ALIM-2026-1004', 'Guadalupe Olvera Martínez', 'Tzibanzá', 'Despensa Básica Familiar', 1, '2026-10-02', 'Zona rural alejadas', 'Admin'),
(5, 'ALIM-2026-1005', 'Javier Ramos Vega', 'La Campana', 'Dotación Adulto Mayor', 1, '2026-10-03', 'Beneficiario recurrente', 'Admin'),
(6, 'ALIM-2026-1006', 'Carmen Landeta Gil', 'Macavaca', 'Despensa Básica Familiar', 2, '2026-10-03', 'Familia numerosa', 'Admin'),
(7, 'ALIM-2026-1007', 'Rigoberto Mendoza Soria', 'San Gaspar', 'Paquete Desayunos Escolares Fríos', 1, '2026-10-04', 'Jardín de Niños local', 'Admin'),
(8, 'ALIM-2026-1008', 'Esperanza Rubio Pérez', 'Higuerillas', 'Dotación Adulto Mayor', 1, '2026-10-04', 'Entrega a domicilio', 'Admin'),
(9, 'ALIM-2026-1009', 'Héctor Manuel Cruz', 'Cadereyta Centro', 'Despensa Básica Familiar', 1, '2026-10-05', 'Zona urbana', 'Admin'),
(10, 'ALIM-2026-1010', 'Patricia Alanís Montero', 'Boyé', 'Paquete Desayunos Escolares Fríos', 3, '2026-10-05', 'Comité de padres de familia', 'Admin'),
(11, 'ALIM-2026-1011', 'Sofía Gómez Villeda', 'Vizarrón de Montes', 'Despensa Básica Familiar', 1, '2026-10-06', 'Sin novedad', 'Admin'),
(12, 'ALIM-2026-1012', 'Esteban Pineda Silva', 'El Doctor', 'Dotación Adulto Mayor', 2, '2026-10-06', 'Zona alta de sierra', 'Admin'),
(13, 'ALIM-2026-1013', 'Rosaura Montero Zárate', 'Tzibanzá', 'Despensa Básica Familiar', 1, '2026-10-07', 'Apoyo alimentario ordinario', 'Admin'),
(14, 'ALIM-2026-1014', 'Mauricio Camacho Luna', 'La Campana', 'Paquete Desayunos Escolares Fríos', 2, '2026-10-07', 'Escuela primaria rural', 'Admin'),
(15, 'ALIM-2026-1015', 'Claudia Martinez Reséndiz', 'Macavaca', 'Dotación Adulto Mayor', 1, '2026-10-08', 'Adulto mayor registrado', 'Admin'),
(16, 'ALIM-2026-1016', 'Daniela León Solís', 'Higuerillas', 'Despensa Básica Familiar', 1, '2026-10-08', 'Entrega en instalaciones DIF', 'Admin'),
(17, 'ALIM-2026-1017', 'Fernando Cabrera Rangel', 'San Gaspar', 'Despensa Básica Familiar', 2, '2026-10-09', 'Apoyo especial', 'Admin'),
(18, 'ALIM-2026-1018', 'Verónica Daniel Meza', 'Cadereyta Centro', 'Dotación Adulto Mayor', 1, '2026-10-09', 'Padrón municipal', 'Admin'),
(19, 'ALIM-2026-1019', 'Alejandro Pedraza Silva', 'Boyé', 'Paquete Desayunos Escolares Fríos', 1, '2026-10-10', 'Plantel preescolar', 'Admin'),
(20, 'ALIM-2026-1020', 'Maricela Granados Villeda', 'Vizarrón de Montes', 'Despensa Básica Familiar', 1, '2026-10-10', 'Segunda entrega del ciclo', 'Admin'),
(21, 'ALIM-2026-1021', 'Felipe de Jesús Trejo', 'El Doctor', 'Dotación Adulto Mayor', 2, '2026-10-11', 'Comunidad El Doctor', 'Admin'),
(22, 'ALIM-2026-1022', 'Norma Alicia Villeda', 'Tzibanzá', 'Despensa Básica Familiar', 1, '2026-10-11', 'Apoyo social', 'Admin'),
(23, 'ALIM-2026-1023', 'Gerardo Dávila Olvera', 'La Campana', 'Paquete Desayunos Escolares Fríos', 2, '2026-10-12', 'Secundaria técnica', 'Admin'),
(24, 'ALIM-2026-1024', 'Blanca Estela Reséndiz', 'Macavaca', 'Despensa Básica Familiar', 1, '2026-10-12', 'Madre de familia', 'Admin'),
(25, 'ALIM-2026-1025', 'Arturo Meza Godínez', 'Higuerillas', 'Dotación Adulto Mayor', 1, '2026-10-13', 'Control mensual', 'Admin'),
(26, 'ALIM-2026-1026', 'Guadalupe Sánchez Rubio', 'San Gaspar', 'Despensa Básica Familiar', 1, '2026-10-13', 'Entrega ordinaria', 'Admin'),
(27, 'ALIM-2026-1027', 'Ricardo Domínguez Peña', 'Cadereyta Centro', 'Paquete Desayunos Escolares Fríos', 3, '2026-10-14', 'Dotación escolar', 'Admin'),
(28, 'ALIM-2026-1028', 'Gabriela Morales Pineda', 'Boyé', 'Dotación Adulto Mayor', 1, '2026-10-14', 'Tercera edad', 'Admin'),
(29, 'ALIM-2026-1029', 'Samuel Guadarrama Reyes', 'Vizarrón de Montes', 'Despensa Básica Familiar', 2, '2026-10-15', 'Apoyo a familias vulnerables', 'Admin'),
(30, 'ALIM-2026-1030', 'Yolanda Zamudio Torres', 'El Doctor', 'Despensa Básica Familiar', 1, '2026-10-15', 'Entrega final octubre', 'Admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nutricion`
--

CREATE TABLE `nutricion` (
  `id` int NOT NULL COMMENT 'ID único de esta evaluación/registro',
  `folio` varchar(20) NOT NULL COMMENT 'Folio del beneficiario (para agrupar historial)',
  `nombre` varchar(100) NOT NULL,
  `sexo` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `edad` varchar(50) DEFAULT NULL,
  `peso` decimal(5,2) NOT NULL COMMENT 'Peso en kilogramos',
  `talla` decimal(5,2) NOT NULL COMMENT 'Talla en metros',
  `imc` decimal(4,2) DEFAULT NULL COMMENT 'Índice de Masa Corporal (Calculado)',
  `imc_normal` varchar(20) DEFAULT NULL COMMENT 'Diagnóstico (Bajo peso, Normal, Obesidad, etc.)',
  `diagnostico` text COMMENT 'Observaciones y notas del nutriólogo',
  `municipio` varchar(50) DEFAULT NULL,
  `domicilio` text,
  `telefono` varchar(15) DEFAULT NULL,
  `localidad` varchar(50) DEFAULT NULL,
  `mes` varchar(15) NOT NULL COMMENT 'Mes de la evaluación',
  `tutor` varchar(100) DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora del registro',
  `id_usuario` int DEFAULT NULL,
  `dxpt` decimal(10,2) DEFAULT '0.00',
  `dxpe` decimal(10,2) DEFAULT '0.00',
  `dxte` decimal(10,2) DEFAULT '0.00',
  `tipo_localidad` varchar(50) DEFAULT NULL,
  `centro_salud` varchar(100) DEFAULT NULL,
  `grupo_vulnerable` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `nutricion`
--

INSERT INTO `nutricion` (`id`, `folio`, `nombre`, `sexo`, `fecha_nacimiento`, `edad`, `peso`, `talla`, `imc`, `imc_normal`, `diagnostico`, `municipio`, `domicilio`, `telefono`, `localidad`, `mes`, `tutor`, `fecha_registro`, `id_usuario`, `dxpt`, `dxpe`, `dxte`, `tipo_localidad`, `centro_salud`, `grupo_vulnerable`) VALUES
(26, 'DIF-2026-1699', 'dayana resendiz martinez ', 'F', '2023-05-02', '2 años, 8 meses', 13.50, 91.00, 0.00, 'Bajo peso', '', 'Cadereyta', 'conocido', '4412053146', 'altamira la bondotita', 'Octubre', 'alicia martinez gutierrez', '2026-01-26 18:25:40', 1, 0.00, 0.00, 0.00, NULL, NULL, NULL),
(32, 'DIF-2026-1701', 'Juan Pérez Sánchez', 'M', '2018-04-12', '8 años, 6 meses', 24.50, 1.25, 15.68, 'Normal', 'Peso adecuado para la edad', 'Cadereyta de Montes', 'Calle Juárez S/N', '4411019020', 'Boyé', 'Octubre', 'María Sánchez Pérez', '2026-10-01 16:30:00', 1, 0.00, 0.00, 0.00, 'Urbana', 'Boyé', 'Ninguno'),
(33, 'DIF-2026-1702', 'Guadalupe Martínez Reséndiz', 'F', '2021-02-15', '5 años, 8 meses', 16.00, 1.05, 14.51, 'Normal', 'Peso normal', 'Cadereyta de Montes', 'Av. Hidalgo #12', '4411029021', 'Vizarrón de Montes', 'Octubre', 'Carmen Reséndiz', '2026-10-01 17:15:00', 1, 0.00, 0.00, 0.00, 'Semiurbana', 'Vizarrón', 'Madre Soltera'),
(34, 'DIF-2026-1703', 'Carlos Trejo Morales', 'M', '2015-09-20', '11 años, 1 mes', 38.00, 1.40, 19.39, 'Sobrepeso', 'Tendencia a sobrepeso', 'Cadereyta de Montes', 'Barrio de Guadalupe', '4411039022', 'El Doctor', 'Octubre', 'Rosa Morales', '2026-10-02 15:00:00', 1, 0.00, 0.00, 0.00, 'Rural', 'El Doctor', 'Ninguno'),
(35, 'DIF-2026-1704', 'Ximena Gudiño Olvera', 'F', '2023-06-10', '3 años, 4 meses', 12.80, 0.92, 15.13, 'Normal', 'Nutrición adecuada', 'Cadereyta de Montes', 'Carretera Principal S/N', '4411049023', 'Tzibanzá', 'Octubre', 'Lucía Olvera', '2026-10-02 16:20:00', 1, 0.00, 0.00, 0.00, 'Rural', 'Tzibanzá', 'Pobreza extrema'),
(36, 'DIF-2026-1705', 'Alejandro Ramos Vega', 'M', '2019-11-05', '6 años, 11 meses', 19.00, 1.15, 14.37, 'Bajo peso', 'Peso bajo para la talla', 'Cadereyta de Montes', 'Calle Real #45', '4411059024', 'La Campana', 'Octubre', 'Sofia Vega', '2026-10-03 18:00:00', 1, 0.00, 0.00, 0.00, 'Rural', 'La Campana', 'Nutrición vulnerable'),
(37, 'DIF-2026-1706', 'Fernanda Castillo Landeta', 'F', '2017-01-18', '9 años, 8 meses', 31.00, 1.32, 17.78, 'Normal', 'Eutrófico', 'Cadereyta de Montes', 'Calle Del Pozo #8', '4411069025', 'Macavaca', 'Octubre', 'Patricia Landeta', '2026-10-03 19:10:00', 1, 0.00, 0.00, 0.00, 'Rural', 'Macavaca', 'Ninguno'),
(38, 'DIF-2026-1707', 'Uriel Mendoza Trejo', 'M', '2022-08-22', '4 años, 2 meses', 15.20, 1.01, 14.90, 'Normal', 'Desarrollo normal', 'Cadereyta de Montes', 'Callejon del Sapo', '4411079026', 'San Gaspar', 'Octubre', 'Margarita Trejo', '2026-10-04 15:45:00', 1, 0.00, 0.00, 0.00, 'Rural', 'San Gaspar', 'Ninguno'),
(39, 'DIF-2026-1708', 'Valeria Hernández Rubio', 'F', '2016-12-03', '9 años, 10 meses', 35.50, 1.35, 19.48, 'Sobrepeso', 'Límite de sobrepeso', 'Cadereyta de Montes', 'Centro S/N', '4411089027', 'Higuerillas', 'Octubre', 'Juana Rubio', '2026-10-04 16:50:00', 1, 0.00, 0.00, 0.00, 'Semiurbana', 'Higuerillas', 'Ninguno'),
(40, 'DIF-2026-1709', 'Diego Armando Cruz', 'M', '2020-05-14', '6 años, 5 meses', 18.00, 1.12, 14.34, 'Normal', 'Peso saludable', 'Cadereyta de Montes', 'La Loma S/N', '4411099028', 'San Juan Buenavista', 'Octubre', 'Beatriz Cruz', '2026-10-05 18:30:00', 1, 0.00, 0.00, 0.00, 'Rural', 'San Juan', 'Ninguno'),
(41, 'DIF-2026-1710', 'Camila Torres Alanís', 'F', '2018-10-30', '7 años, 11 meses', 22.00, 1.20, 15.28, 'Normal', 'Adecuado', 'Cadereyta de Montes', 'Calle Principal #10', '4411109029', 'Praderas del Llano', 'Octubre', 'Daniela Alanís', '2026-10-05 19:40:00', 1, 0.00, 0.00, 0.00, 'Urbana', 'Cadereyta Centro', 'Ninguno'),
(42, 'DIF-2026-1711', 'Kevin Yael Gómez', 'M', '2021-07-07', '5 años, 3 meses', 17.10, 1.08, 14.66, 'Normal', 'Sin alteraciones', 'Cadereyta de Montes', 'Barrio de la Cruz', '4411119030', 'El Palmar', 'Octubre', 'Esperanza Gómez', '2026-10-06 15:15:00', 1, 0.00, 0.00, 0.00, 'Rural', 'El Palmar', 'Discapacidad'),
(43, 'DIF-2026-1712', 'Regina Mendiola Pineda', 'F', '2019-03-25', '7 años, 6 meses', 21.50, 1.18, 15.45, 'Normal', 'Crecimiento óptimo', 'Cadereyta de Montes', 'Camino Real #3', '4411129031', 'Pathé', 'Octubre', 'Gabriela Pineda', '2026-10-06 16:25:00', 1, 0.00, 0.00, 0.00, 'Rural', 'Pathé', 'Ninguno'),
(44, 'DIF-2026-1713', 'Santiago Luna Montero', 'M', '2015-04-11', '11 años, 6 meses', 42.00, 1.45, 20.00, 'Sobrepeso', 'Sobrepeso leve', 'Cadereyta de Montes', 'Av. Juárez #50', '4411139032', 'Boyé', 'Octubre', 'Irene Montero', '2026-10-07 17:00:00', 1, 0.00, 0.00, 0.00, 'Urbana', 'Boyé', 'Ninguno'),
(45, 'DIF-2026-1714', 'Jocelyn Morales Zarate', 'F', '2022-01-09', '4 años, 9 meses', 14.50, 0.98, 15.10, 'Normal', 'Peso y talla acordes', 'Cadereyta de Montes', 'Calle Nueva S/N', '4411149033', 'Vizarrón de Montes', 'Octubre', 'Adriana Zarate', '2026-10-07 18:15:00', 1, 0.00, 0.00, 0.00, 'Semiurbana', 'Vizarrón', 'Ninguno'),
(46, 'DIF-2026-1715', 'Mateo Alfaro Camacho', 'M', '2017-08-16', '9 años, 2 meses', 28.00, 1.28, 17.07, 'Normal', 'Saludable', 'Cadereyta de Montes', 'Barrio Abajo', '4411159034', 'El Doctor', 'Octubre', 'Esteban Alfaro', '2026-10-08 15:30:00', 1, 0.00, 0.00, 0.00, 'Rural', 'El Doctor', 'Pobreza extrema'),
(47, 'DIF-2026-1716', 'Dayana Resendiz Martinez', 'F', '2023-05-02', '3 años, 5 meses', 13.80, 0.93, 15.97, 'Normal', 'Segunda visita - Evolución favorable', 'Cadereyta de Montes', 'Conocido', '4412053146', 'Altamira la Bondotita', 'Octubre', 'Alicia Martinez Gutierrez', '2026-10-08 16:45:00', 1, 0.00, 0.00, 0.00, 'Rural', 'Altamira', 'Ninguno'),
(48, 'DIF-2026-1717', 'Emiliano Garza León', 'M', '2018-11-28', '7 años, 10 meses', 23.00, 1.22, 15.44, 'Normal', 'Normal', 'Cadereyta de Montes', 'Calle Principal #4', '4411179036', 'Tzibanzá', 'Octubre', 'Claudia León', '2026-10-09 17:20:00', 1, 0.00, 0.00, 0.00, 'Rural', 'Tzibanzá', 'Ninguno'),
(49, 'DIF-2026-1718', 'Jimena Solís Cabrera', 'F', '2020-09-04', '6 años, 1 mes', 17.50, 1.10, 14.46, 'Normal', 'Buen desarrollo', 'Cadereyta de Montes', 'La Joya S/N', '4411189037', 'La Campana', 'Octubre', 'Verónica Cabrera', '2026-10-09 18:50:00', 1, 0.00, 0.00, 0.00, 'Rural', 'La Campana', 'Ninguno'),
(50, 'DIF-2026-1719', 'Iker Daniel Pedraza', 'M', '2016-06-19', '10 años, 4 meses', 33.00, 1.34, 18.38, 'Normal', 'Eutrófico', 'Cadereyta de Montes', 'Centro #22', '4411199038', 'Macavaca', 'Octubre', 'Rocío Daniel', '2026-10-10 15:00:00', 1, 0.00, 0.00, 0.00, 'Rural', 'Macavaca', 'Ninguno'),
(51, 'DIF-2026-1720', 'Aitana Cruz Montero', 'F', '2021-12-11', '4 años, 10 meses', 15.00, 1.00, 15.00, 'Normal', 'Peso adecuado', 'Cadereyta de Montes', 'Calle del Puente', '4411209039', 'San Gaspar', 'Octubre', 'Rosa Montero', '2026-10-10 16:15:00', 1, 0.00, 0.00, 0.00, 'Rural', 'San Gaspar', 'Madre Soltera'),
(52, 'DIF-2026-1721', 'Leonardo Rangel Silva', 'M', '2019-02-08', '7 años, 8 meses', 20.50, 1.17, 14.98, 'Normal', 'Adecuado', 'Cadereyta de Montes', 'Barrio Arriba', '4411219040', 'Higuerillas', 'Octubre', 'Martha Silva', '2026-10-11 17:00:00', 1, 0.00, 0.00, 0.00, 'Semiurbana', 'Higuerillas', 'Ninguno'),
(53, 'DIF-2026-1722', 'Valeria Estefanía Silva', 'F', '2015-10-15', '11 años', 39.50, 1.42, 19.57, 'Normal', 'Saludable', 'Cadereyta de Montes', 'Camino Viejo S/N', '4411229041', 'San Juan Buenavista', 'Octubre', 'Leticia Silva', '2026-10-11 18:20:00', 1, 0.00, 0.00, 0.00, 'Rural', 'San Juan', 'Ninguno'),
(54, 'DIF-2026-1723', 'Braulio Meza Vázquez', 'M', '2022-04-20', '4 años, 5 meses', 16.20, 1.03, 15.27, 'Normal', 'Crecimiento regular', 'Cadereyta de Montes', 'Av. Central #9', '4411239042', 'Praderas del Llano', 'Octubre', 'Karla Vázquez', '2026-10-12 15:30:00', 1, 0.00, 0.00, 0.00, 'Urbana', 'Cadereyta Centro', 'Ninguno'),
(55, 'DIF-2026-1724', 'Danna Paola Villeda', 'F', '2018-07-27', '8 años, 2 meses', 25.00, 1.24, 16.28, 'Normal', 'Sin problemas de peso', 'Cadereyta de Montes', 'Callejon San José', '4411249043', 'El Palmar', 'Octubre', 'Juana Villeda', '2026-10-12 16:40:00', 1, 0.00, 0.00, 0.00, 'Rural', 'El Palmar', 'Ninguno'),
(56, 'DIF-2026-1725', 'Gael Alejandro Dávila', 'M', '2020-01-30', '6 años, 8 meses', 18.50, 1.13, 14.51, 'Normal', 'Peso idóneo', 'Cadereyta de Montes', 'Carretera San Joaquín', '4411259044', 'Pathé', 'Octubre', 'Mariana Dávila', '2026-10-13 17:15:00', 1, 0.00, 0.00, 0.00, 'Rural', 'Pathé', 'Ninguno'),
(57, 'DIF-2026-1726', 'Zaraí Reséndiz Olvera', 'F', '2017-05-12', '9 años, 5 meses', 29.50, 1.30, 17.46, 'Normal', 'Eutrófica', 'Cadereyta de Montes', 'Centro', '4411269045', 'Boyé', 'Octubre', 'Ignacia Olvera', '2026-10-13 18:30:00', 1, 0.00, 0.00, 0.00, 'Urbana', 'Boyé', 'Ninguno'),
(58, 'DIF-2026-1727', 'Tadeo Godínez Meza', 'M', '2023-03-01', '3 años, 7 meses', 13.50, 0.95, 14.96, 'Normal', 'Tercera visita - Control exitoso', 'Cadereyta de Montes', 'Principal #2', '4411279046', 'Vizarrón de Montes', 'Octubre', 'Sonia Meza', '2026-10-14 15:00:00', 1, 0.00, 0.00, 0.00, 'Semiurbana', 'Vizarrón', 'Ninguno'),
(59, 'DIF-2026-1728', 'Renata Trejo Granados', 'F', '2019-08-19', '7 años, 2 meses', 21.00, 1.16, 15.60, 'Normal', 'Adecuado', 'Cadereyta de Montes', 'El Saucito', '4411289047', 'El Doctor', 'Octubre', 'Teresa Granados', '2026-10-14 16:10:00', 1, 0.00, 0.00, 0.00, 'Rural', 'El Doctor', 'Pobreza extrema'),
(60, 'DIF-2026-1729', 'Dominik Reséndiz Vega', 'M', '2016-11-25', '9 años, 10 meses', 34.00, 1.36, 18.37, 'Normal', 'Normal', 'Cadereyta de Montes', 'La Ribera', '4411299048', 'Tzibanzá', 'Octubre', 'Esperanza Vega', '2026-10-15 17:00:00', 1, 0.00, 0.00, 0.00, 'Rural', 'Tzibanzá', 'Ninguno'),
(61, 'DIF-2026-1730', 'Alisson Yaretzi Rubio', 'F', '2021-05-05', '5 años, 5 meses', 16.80, 1.06, 14.95, 'Normal', 'Segunda visita - Buen avance nutricional', 'Cadereyta de Montes', 'Barrio de la Cruz', '4411309049', 'La Campana', 'Octubre', 'Guadalupe Rubio', '2026-10-15 18:20:00', 1, 0.00, 0.00, 0.00, 'Rural', 'La Campana', 'Ninguno'),
(62, 'DIF-2026-1730', 'Alisson Yaretzi Rubio', 'F', '2021-05-05', '4 años, 6 meses', 15.10, 1.01, 14.80, 'Normal', 'Evaluación inicial - Mes 1', 'Cadereyta de Montes', 'Barrio de la Cruz', '4411309049', 'La Campana', 'Enero', 'Guadalupe Rubio', '2026-11-23 22:20:58', 1, 0.00, 0.00, 0.00, 'Rural', 'La Campana', 'Ninguno'),
(63, 'DIF-2026-1730', 'Alisson Yaretzi Rubio', 'F', '2021-05-05', '4 años, 7 meses', 15.25, 1.02, 14.66, 'Normal', 'Seguimiento mensual - Mes 2', 'Cadereyta de Montes', 'Barrio de la Cruz', '4411309049', 'La Campana', 'Febrero', 'Guadalupe Rubio', '2026-11-23 22:20:58', 1, 0.00, 0.00, 0.00, 'Rural', 'La Campana', 'Ninguno'),
(64, 'DIF-2026-1730', 'Alisson Yaretzi Rubio', 'F', '2021-05-05', '4 años, 8 meses', 15.40, 1.02, 14.80, 'Normal', 'Seguimiento mensual - Mes 3', 'Cadereyta de Montes', 'Barrio de la Cruz', '4411309049', 'La Campana', 'Marzo', 'Guadalupe Rubio', '2026-11-23 22:20:58', 1, 0.00, 0.00, 0.00, 'Rural', 'La Campana', 'Ninguno'),
(65, 'DIF-2026-1730', 'Alisson Yaretzi Rubio', 'F', '2021-05-05', '4 años, 9 meses', 15.60, 1.03, 14.70, 'Normal', 'Seguimiento mensual - Mes 4', 'Cadereyta de Montes', 'Barrio de la Cruz', '4411309049', 'La Campana', 'Abril', 'Guadalupe Rubio', '2026-11-23 22:20:58', 1, 0.00, 0.00, 0.00, 'Rural', 'La Campana', 'Ninguno'),
(66, 'DIF-2026-1730', 'Alisson Yaretzi Rubio', 'F', '2021-05-05', '4 años, 10 meses', 15.75, 1.03, 14.84, 'Normal', 'Seguimiento mensual - Mes 5', 'Cadereyta de Montes', 'Barrio de la Cruz', '4411309049', 'La Campana', 'Mayo', 'Guadalupe Rubio', '2026-11-23 22:20:58', 1, 0.00, 0.00, 0.00, 'Rural', 'La Campana', 'Ninguno'),
(67, 'DIF-2026-1730', 'Alisson Yaretzi Rubio', 'F', '2021-05-05', '4 años, 11 meses', 15.90, 1.04, 14.70, 'Normal', 'Seguimiento mensual - Mes 6', 'Cadereyta de Montes', 'Barrio de la Cruz', '4411309049', 'La Campana', 'Junio', 'Guadalupe Rubio', '2026-11-23 22:20:58', 1, 0.00, 0.00, 0.00, 'Rural', 'La Campana', 'Ninguno'),
(68, 'DIF-2026-1730', 'Alisson Yaretzi Rubio', 'F', '2021-05-05', '5 años, 0 meses', 16.10, 1.04, 14.89, 'Normal', 'Seguimiento mensual - Mes 7', 'Cadereyta de Montes', 'Barrio de la Cruz', '4411309049', 'La Campana', 'Julio', 'Guadalupe Rubio', '2026-11-23 22:20:58', 1, 0.00, 0.00, 0.00, 'Rural', 'La Campana', 'Ninguno'),
(69, 'DIF-2026-1730', 'Alisson Yaretzi Rubio', 'F', '2021-05-05', '5 años, 1 mes', 16.25, 1.05, 14.74, 'Normal', 'Seguimiento mensual - Mes 8', 'Cadereyta de Montes', 'Barrio de la Cruz', '4411309049', 'La Campana', 'Agosto', 'Guadalupe Rubio', '2026-11-23 22:20:58', 1, 0.00, 0.00, 0.00, 'Rural', 'La Campana', 'Ninguno'),
(70, 'DIF-2026-1730', 'Alisson Yaretzi Rubio', 'F', '2021-05-05', '5 años, 2 meses', 16.45, 1.05, 14.92, 'Normal', 'Seguimiento mensual - Mes 9', 'Cadereyta de Montes', 'Barrio de la Cruz', '4411309049', 'La Campana', 'Septiembre', 'Guadalupe Rubio', '2026-11-23 22:20:58', 1, 0.00, 0.00, 0.00, 'Rural', 'La Campana', 'Ninguno'),
(71, 'DIF-2026-1730', 'Alisson Yaretzi Rubio', 'F', '2021-05-05', '5 años, 3 meses', 16.60, 1.06, 14.77, 'Normal', 'Seguimiento mensual - Mes 10', 'Cadereyta de Montes', 'Barrio de la Cruz', '4411309049', 'La Campana', 'Octubre 1', 'Guadalupe Rubio', '2026-11-23 22:20:58', 1, 0.00, 0.00, 0.00, 'Rural', 'La Campana', 'Ninguno'),
(72, 'DIF-2026-1730', 'Alisson Yaretzi Rubio', 'F', '2021-05-05', '5 años, 4 meses', 16.70, 1.06, 14.86, 'Normal', 'Seguimiento mensual - Mes 11', 'Cadereyta de Montes', 'Barrio de la Cruz', '4411309049', 'La Campana', 'Noviembre', 'Guadalupe Rubio', '2026-11-23 22:20:58', 1, 0.00, 0.00, 0.00, 'Rural', 'La Campana', 'Ninguno'),
(73, 'DIF-2026-1730', 'Alisson Yaretzi Rubio', 'F', '2021-05-05', '5 años, 5 meses', 16.80, 1.06, 14.95, 'Normal', 'Seguimiento mensual - Mes 12', 'Cadereyta de Montes', 'Barrio de la Cruz', '4411309049', 'La Campana', 'Diciembre', 'Guadalupe Rubio', '2026-11-23 22:20:58', 1, 0.00, 0.00, 0.00, 'Rural', 'La Campana', 'Ninguno'),
(74, 'DIF-2026-1701', 'Juan Pérez Sánchez', 'M', '2018-04-12', '8 años, 3 meses', 23.80, 1.23, 15.73, 'Normal', 'Control inicial - Mes 1', 'Cadereyta de Montes', 'Calle Juárez S/N', '4411019020', 'Boyé', 'Julio', 'María Sánchez Pérez', '2026-11-23 22:24:15', 1, 0.00, 0.00, 0.00, 'Urbana', 'Boyé', 'Ninguno'),
(75, 'DIF-2026-1701', 'Juan Pérez Sánchez', 'M', '2018-04-12', '8 años, 4 meses', 24.05, 1.24, 15.64, 'Normal', 'Seguimiento mensual - Mes 2', 'Cadereyta de Montes', 'Calle Juárez S/N', '4411019020', 'Boyé', 'Agosto', 'María Sánchez Pérez', '2026-11-23 22:24:15', 1, 0.00, 0.00, 0.00, 'Urbana', 'Boyé', 'Ninguno'),
(76, 'DIF-2026-1701', 'Juan Pérez Sánchez', 'M', '2018-04-12', '8 años, 5 meses', 24.30, 1.24, 15.80, 'Normal', 'Seguimiento mensual - Mes 3', 'Cadereyta de Montes', 'Calle Juárez S/N', '4411019020', 'Boyé', 'Septiembre', 'María Sánchez Pérez', '2026-11-23 22:24:15', 1, 0.00, 0.00, 0.00, 'Urbana', 'Boyé', 'Ninguno'),
(77, 'DIF-2026-1701', 'Juan Pérez Sánchez', 'M', '2018-04-12', '8 años, 7 meses', 24.70, 1.25, 15.81, 'Normal', 'Control reciente - Mes 4', 'Cadereyta de Montes', 'Calle Juárez S/N', '4411019020', 'Boyé', 'Noviembre', 'María Sánchez Pérez', '2026-11-23 22:24:15', 1, 0.00, 0.00, 0.00, 'Urbana', 'Boyé', 'Ninguno'),
(78, 'DIF-2026-1702', 'Guadalupe Martínez Reséndiz', 'F', '2021-02-15', '5 años, 6 meses', 15.50, 1.04, 14.33, 'Normal', 'Evaluación previa - Mes 1', 'Cadereyta de Montes', 'Av. Hidalgo #12', '4411029021', 'Vizarrón de Montes', 'Agosto', 'Carmen Reséndiz', '2026-11-23 22:24:15', 1, 0.00, 0.00, 0.00, 'Semiurbana', 'Vizarrón', 'Madre Soltera'),
(79, 'DIF-2026-1702', 'Guadalupe Martínez Reséndiz', 'F', '2021-02-15', '5 años, 7 meses', 15.75, 1.04, 14.56, 'Normal', 'Seguimiento mensual - Mes 2', 'Cadereyta de Montes', 'Av. Hidalgo #12', '4411029021', 'Vizarrón de Montes', 'Septiembre', 'Carmen Reséndiz', '2026-11-23 22:24:15', 1, 0.00, 0.00, 0.00, 'Semiurbana', 'Vizarrón', 'Madre Soltera'),
(80, 'DIF-2026-1702', 'Guadalupe Martínez Reséndiz', 'F', '2021-02-15', '5 años, 9 meses', 16.20, 1.06, 14.42, 'Normal', 'Control reciente - Mes 3', 'Cadereyta de Montes', 'Av. Hidalgo #12', '4411029021', 'Vizarrón de Montes', 'Noviembre', 'Carmen Reséndiz', '2026-11-23 22:24:15', 1, 0.00, 0.00, 0.00, 'Semiurbana', 'Vizarrón', 'Madre Soltera'),
(81, 'DIF-2026-1703', 'Carlos Trejo Morales', 'M', '2015-09-20', '10 años, 11 meses', 37.20, 1.39, 19.25, 'Sobrepeso', 'Registro inicial - Mes 1', 'Cadereyta de Montes', 'Barrio de Guadalupe', '4411039022', 'El Doctor', 'Agosto', 'Rosa Morales', '2026-11-23 22:24:15', 1, 0.00, 0.00, 0.00, 'Rural', 'El Doctor', 'Ninguno'),
(82, 'DIF-2026-1703', 'Carlos Trejo Morales', 'M', '2015-09-20', '11 años, 0 meses', 37.60, 1.40, 19.18, 'Sobrepeso', 'Ajuste de dieta y actividad - Mes 2', 'Cadereyta de Montes', 'Barrio de Guadalupe', '4411039022', 'El Doctor', 'Septiembre', 'Rosa Morales', '2026-11-23 22:24:15', 1, 0.00, 0.00, 0.00, 'Rural', 'El Doctor', 'Ninguno'),
(83, 'DIF-2026-1703', 'Carlos Trejo Morales', 'M', '2015-09-20', '11 años, 2 meses', 38.10, 1.41, 19.16, 'Sobrepeso', 'Control reciente - Mes 3', 'Cadereyta de Montes', 'Barrio de Guadalupe', '4411039022', 'El Doctor', 'Noviembre', 'Rosa Morales', '2026-11-23 22:24:15', 1, 0.00, 0.00, 0.00, 'Rural', 'El Doctor', 'Ninguno'),
(84, 'DIF-2026-1729', 'Dominik Reséndiz Vega', 'M', '2016-11-25', '9 años, 9 meses', 33.50, 1.35, 18.38, 'Normal', 'Evaluación previa - Mes 1', 'Cadereyta de Montes', 'La Ribera', '4411299048', 'Tzibanzá', 'Septiembre', 'Esperanza Vega', '2026-11-23 22:24:15', 1, 0.00, 0.00, 0.00, 'Rural', 'Tzibanzá', 'Ninguno'),
(85, 'DIF-2026-1729', 'Dominik Reséndiz Vega', 'M', '2016-11-25', '9 años, 11 meses', 34.40, 1.37, 18.33, 'Normal', 'Control reciente - Mes 2', 'Cadereyta de Montes', 'La Ribera', '4411299048', 'Tzibanzá', 'Noviembre', 'Esperanza Vega', '2026-11-23 22:24:15', 1, 0.00, 0.00, 0.00, 'Rural', 'Tzibanzá', 'Ninguno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tabla_referencia_seseq`
--

CREATE TABLE `tabla_referencia_seseq` (
  `id` int NOT NULL,
  `talla_cm` decimal(5,2) NOT NULL,
  `sexo` varchar(20) NOT NULL,
  `p5` decimal(5,2) DEFAULT NULL,
  `p10` decimal(5,2) DEFAULT NULL,
  `p50` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transporte`
--

CREATE TABLE `transporte` (
  `id_transporte` int NOT NULL,
  `folio_servicio` varchar(50) NOT NULL,
  `unidad` varchar(100) NOT NULL,
  `operador` varchar(100) NOT NULL,
  `ruta_destino` varchar(150) NOT NULL,
  `tipo_servicio` varchar(100) NOT NULL,
  `pasajeros_pasajeras` int NOT NULL,
  `acompanantes` int DEFAULT '0',
  `fecha_servicio` date NOT NULL,
  `hora_salida` time DEFAULT '08:00:00',
  `hora_regreso` time DEFAULT '14:00:00',
  `km_inicial` decimal(10,2) DEFAULT '0.00',
  `km_final` decimal(10,2) DEFAULT '0.00',
  `estatus_mantenimiento` varchar(50) NOT NULL,
  `observaciones` text,
  `usuario_atiende` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `transporte`
--

INSERT INTO `transporte` (`id_transporte`, `folio_servicio`, `unidad`, `operador`, `ruta_destino`, `tipo_servicio`, `pasajeros_pasajeras`, `acompanantes`, `fecha_servicio`, `hora_salida`, `hora_regreso`, `km_inicial`, `km_final`, `estatus_mantenimiento`, `observaciones`, `usuario_atiende`) VALUES
(1, 'TRANS-2026-4001', 'Unidad 01 - Nissan Urvan Adaptada', 'Roberto Trejo Cruz', 'Cadereyta - CRIT Querétaro', 'Traslado Médico Especializado', 4, 0, '2026-10-01', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Traslado de pacientes a terapia física', 'Admin'),
(2, 'TRANS-2026-4002', 'Unidad 02 - Chevrolet Aveo', 'Juan Carlos Reséndiz', 'Cadereyta - Hospital General QRO', 'Traslado Médico Especializado', 2, 0, '2026-10-01', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Cita médica agendada en oncología', 'Admin'),
(3, 'TRANS-2026-4003', 'Unidad 03 - Ford Transit Custom', 'Felipe de Jesús Soria', 'Ruta Boyé - Cadereyta Centro', 'Ruta Comunitaria Inclusiva', 12, 0, '2026-10-02', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Traslado de grupo de la tercera edad', 'Admin'),
(4, 'TRANS-2026-4004', 'Unidad 04 - Nissan NP300 Pick-Up', 'Mario Mendoza Alanís', 'Comunidad El Doctor - Cabecera', 'Apoyo de Alimentos en Campo', 2, 0, '2026-10-02', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Entrega de apoyos e insumos', 'Admin'),
(5, 'TRANS-2026-4005', 'Unidad 05 - Toyota Avanza', 'Esteban Pineda Silva', 'Ruta Vizarrón - Cadereyta', 'Traslado Evento Social / Cultural', 5, 0, '2026-10-03', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Personal operativo en campo', 'Admin'),
(6, 'TRANS-2026-4006', 'Unidad 06 - Nissan March', 'Luis Fernando Ramos', 'Cadereyta Centro (Urgencia local)', 'Traslado Médico Especializado', 1, 0, '2026-10-03', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Traslado a centro de salud local', 'Admin'),
(7, 'TRANS-2026-4007', 'Unidad 01 - Nissan Urvan Adaptada', 'Roberto Trejo Cruz', 'Cadereyta - CETS Querétaro', 'Traslado Médico Especializado', 3, 0, '2026-10-04', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Pacientes en silla de ruedas a hemodiálisis', 'Admin'),
(8, 'TRANS-2026-4008', 'Unidad 02 - Chevrolet Aveo', 'Juan Carlos Reséndiz', 'Cadereyta - IMSS San Juan del Río', 'Traslado Médico Especializado', 2, 0, '2026-10-04', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Consulta de especialidad', 'Admin'),
(9, 'TRANS-2026-4009', 'Unidad 03 - Ford Transit Custom', 'Felipe de Jesús Soria', 'Cadereyta - Querétaro Centro', 'Traslado Evento Social / Cultural', 14, 0, '2026-10-05', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Viaje cultural con adultos mayores', 'Admin'),
(10, 'TRANS-2026-4010', 'Unidad 04 - Nissan NP300 Pick-Up', 'Mario Mendoza Alanís', 'Ruta Tzibanzá - La Campana', 'Apoyo de Alimentos en Campo', 2, 0, '2026-10-05', '08:00:00', '14:00:00', 0.00, 0.00, 'En Mantenimiento Preventivo', 'Servicio de cambio de aceite y frenos', 'Admin'),
(11, 'TRANS-2026-4011', 'Unidad 05 - Toyota Avanza', 'Esteban Pineda Silva', 'Ruta Macavaca - Higuerillas', 'Ruta Comunitaria Inclusiva', 6, 0, '2026-10-06', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Visitas domiciliarias de trabajo social', 'Admin'),
(12, 'TRANS-2026-4012', 'Unidad 01 - Nissan Urvan Adaptada', 'Roberto Trejo Cruz', 'Cadereyta - CRIT Querétaro', 'Traslado Médico Especializado', 4, 0, '2026-10-06', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Citas matutinas de rehabilitación', 'Admin'),
(13, 'TRANS-2026-4013', 'Unidad 02 - Chevrolet Aveo', 'Juan Carlos Reséndiz', 'Cadereyta - Hospital de Especialidades', 'Traslado Médico Especializado', 2, 0, '2026-10-07', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Regreso sin novedades a las 14:00 hrs', 'Admin'),
(14, 'TRANS-2026-4014', 'Unidad 03 - Ford Transit Custom', 'Felipe de Jesús Soria', 'Ruta San Gaspar - Cadereyta', 'Ruta Comunitaria Inclusiva', 10, 0, '2026-10-07', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Servicio a pacientes de comunidades', 'Admin'),
(15, 'TRANS-2026-4015', 'Unidad 06 - Nissan March', 'Luis Fernando Ramos', 'Cadereyta - Cabecera Municipal', 'Traslado Evento Social / Cultural', 2, 0, '2026-10-08', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Trámites administrativos del DIF', 'Admin'),
(16, 'TRANS-2026-4016', 'Unidad 04 - Nissan NP300 Pick-Up', 'Mario Mendoza Alanís', 'Cabecera - El Doctor (Sierra)', 'Apoyo de Alimentos en Campo', 2, 0, '2026-10-08', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Distribución de material alimentario', 'Admin'),
(17, 'TRANS-2026-4017', 'Unidad 01 - Nissan Urvan Adaptada', 'Roberto Trejo Cruz', 'Cadereyta - Instituto de Rehabilitación', 'Traslado Médico Especializado', 5, 0, '2026-10-09', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Traslado inclusivo matutino', 'Admin'),
(18, 'TRANS-2026-4018', 'Unidad 02 - Chevrolet Aveo', 'Juan Carlos Reséndiz', 'Cadereyta - Clínica 1 IMSS QRO', 'Traslado Médico Especializado', 3, 0, '2026-10-09', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Traslado de paciente y acompañante', 'Admin'),
(19, 'TRANS-2026-4019', 'Unidad 05 - Toyota Avanza', 'Esteban Pineda Silva', 'Cadereyta - Centro de Desarrollo', 'Ruta Comunitaria Inclusiva', 6, 0, '2026-10-10', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Grupo de apoyo vulnerabilidad social', 'Admin'),
(20, 'TRANS-2026-4020', 'Unidad 03 - Ford Transit Custom', 'Felipe de Jesús Soria', 'Ruta Boyé - Vizarrón', 'Ruta Comunitaria Inclusiva', 11, 0, '2026-10-10', '08:00:00', '14:00:00', 0.00, 0.00, 'En Mantenimiento Preventivo', 'Alineación y balanceo de llantas', 'Admin'),
(21, 'TRANS-2026-4021', 'Unidad 01 - Nissan Urvan Adaptada', 'Roberto Trejo Cruz', 'Cadereyta - CRIT Querétaro', 'Traslado Médico Especializado', 4, 0, '2026-10-11', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Segunda cita del mes', 'Admin'),
(22, 'TRANS-2026-4022', 'Unidad 02 - Chevrolet Aveo', 'Juan Carlos Reséndiz', 'Cadereyta - CETS Querétaro', 'Traslado Médico Especializado', 2, 0, '2026-10-11', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Tratamiento hemodiálisis', 'Admin'),
(23, 'TRANS-2026-4023', 'Unidad 04 - Nissan NP300 Pick-Up', 'Mario Mendoza Alanís', 'Ruta La Campana - San Gaspar', 'Apoyo de Alimentos en Campo', 2, 0, '2026-10-12', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Supervisión de entregas', 'Admin'),
(24, 'TRANS-2026-4024', 'Unidad 06 - Nissan March', 'Luis Fernando Ramos', 'Cadereyta - Registro Civil / IMSS', 'Traslado Médico Especializado', 1, 0, '2026-10-12', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Apoyo a adulto mayor en gestiones', 'Admin'),
(25, 'TRANS-2026-4025', 'Unidad 05 - Toyota Avanza', 'Esteban Pineda Silva', 'Ruta Tzibanzá - Cadereyta', 'Ruta Comunitaria Inclusiva', 5, 0, '2026-10-13', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Traslado de familias a consulta', 'Admin'),
(26, 'TRANS-2026-4026', 'Unidad 01 - Nissan Urvan Adaptada', 'Roberto Trejo Cruz', 'Cadereyta - Hospital de Especialidades', 'Traslado Médico Especializado', 3, 0, '2026-10-13', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Sillas de ruedas aseguradas', 'Admin'),
(27, 'TRANS-2026-4027', 'Unidad 03 - Ford Transit Custom', 'Felipe de Jesús Soria', 'Ruta El Doctor - Cadereyta Centro', 'Ruta Comunitaria Inclusiva', 13, 0, '2026-10-14', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Comunidad de la zona serrana', 'Admin'),
(28, 'TRANS-2026-4028', 'Unidad 02 - Chevrolet Aveo', 'Juan Carlos Reséndiz', 'Cadereyta - Hospital General QRO', 'Traslado Médico Especializado', 2, 0, '2026-10-14', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Revisión médica mensual', 'Admin'),
(29, 'TRANS-2026-4029', 'Unidad 04 - Nissan NP300 Pick-Up', 'Mario Mendoza Alanís', 'Ruta Higuerillas - Macavaca', 'Apoyo de Alimentos en Campo', 2, 0, '2026-10-15', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Distribución en comedores comunitarios', 'Admin'),
(30, 'TRANS-2026-4030', 'Unidad 01 - Nissan Urvan Adaptada', 'Roberto Trejo Cruz', 'Cadereyta - CRIT Querétaro', 'Traslado Médico Especializado', 4, 0, '2026-10-15', '08:00:00', '14:00:00', 0.00, 0.00, 'En Operación Optima', 'Cierre de itinerario mensual', 'Admin'),
(31, 'TRANS-2026-1631', 'Unidad 01 - Nissan Urvan Adaptada', 'Roberto Trejo Cruz', 'Cadereyta - Hospital del Niño y la Mujer QRO', 'Traslado Médico Especializado', 3, 2, '2026-11-23', '03:56:00', '14:45:00', 45120.00, 45265.00, 'En Operación Optima', 'Traslado programado sin contratiempos, unidad limpia y en buen estado', 'Admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL,
  `usuario` varchar(50) NOT NULL COMMENT 'Nombre de usuario para el login',
  `contraseña` varchar(255) NOT NULL COMMENT 'Contraseña (Plana en este ejemplo, se recomienda usar HASH)',
  `rol` enum('Admin','Nutri','Alimentos F','Alimentos E','Transporte') NOT NULL,
  `nombre_completo` varchar(100) DEFAULT NULL COMMENT 'Nombre del empleado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `contraseña`, `rol`, `nombre_completo`) VALUES
(1, 'Admin', '1234', 'Admin', 'Administrador General'),
(2, 'Nutri', '1234', 'Nutri', 'Nutrióloga DIF'),
(3, 'alimentos_f', '1234', 'Alimentos F', 'Alimentos'),
(4, 'alimentos_e', '1234', 'Alimentos E', 'Alimentos E'),
(5, 'transporte', '1234', 'Transporte', 'Transporte');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alimentos_calientes`
--
ALTER TABLE `alimentos_calientes`
  ADD PRIMARY KEY (`id_entrega`);

--
-- Indices de la tabla `alimentos_frios`
--
ALTER TABLE `alimentos_frios`
  ADD PRIMARY KEY (`id_entrega`);

--
-- Indices de la tabla `nutricion`
--
ALTER TABLE `nutricion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `tabla_referencia_seseq`
--
ALTER TABLE `tabla_referencia_seseq`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `transporte`
--
ALTER TABLE `transporte`
  ADD PRIMARY KEY (`id_transporte`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alimentos_calientes`
--
ALTER TABLE `alimentos_calientes`
  MODIFY `id_entrega` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `alimentos_frios`
--
ALTER TABLE `alimentos_frios`
  MODIFY `id_entrega` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `nutricion`
--
ALTER TABLE `nutricion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID único de esta evaluación/registro', AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT de la tabla `tabla_referencia_seseq`
--
ALTER TABLE `tabla_referencia_seseq`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transporte`
--
ALTER TABLE `transporte`
  MODIFY `id_transporte` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `nutricion`
--
ALTER TABLE `nutricion`
  ADD CONSTRAINT `nutricion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
