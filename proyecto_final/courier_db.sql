-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-12-2025 a las 01:44:54
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `courier_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id_administrador` int(11) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `nombre_completo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id_administrador`, `usuario`, `contrasena`, `nombre_completo`) VALUES
(1, 'admin', 'admin123', 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coberturas`
--

CREATE TABLE `coberturas` (
  `id_cobertura` int(11) NOT NULL,
  `distrito` varchar(255) NOT NULL,
  `tarifa` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `coberturas`
--

INSERT INTO `coberturas` (`id_cobertura`, `distrito`, `tarifa`) VALUES
(1, 'Ancón', 18.00),
(2, 'Ate', 12.00),
(3, 'Barranco', 10.00),
(4, 'Bellavista', 12.00),
(5, 'Breña', 8.00),
(6, 'Cajamarquilla', 21.00),
(7, 'Callao', 12.00),
(8, 'Carabayllo', 12.00),
(9, 'Carmen de la Legua Reynoso', 12.00),
(10, 'Cercado de Lima', 10.00),
(11, 'Chaclacayo', 17.00),
(12, 'Chorrillos', 12.00),
(13, 'Chosica', 22.00),
(14, 'Cieneguilla', 17.00),
(15, 'Comas', 12.00),
(16, 'El Agustino', 10.00),
(17, 'El Márquez - Callao', 12.00),
(18, 'Envío Agencia Marvisur', 5.00),
(19, 'Envío Agencia Olva Courier', 5.00),
(20, 'Envío Agencia Shalom', 5.00),
(21, 'Huachipa', 14.00),
(22, 'Huaycan', 12.00),
(23, 'Independencia', 12.00),
(24, 'Jesús María', 10.00),
(25, 'Jicamarca - Anexo 22', 14.00),
(26, 'Jicamarca - Anexo 8', 21.00),
(27, 'La Molina', 10.00),
(28, 'La Perla', 12.00),
(29, 'La Punta', 12.00),
(30, 'La Victoria', 10.00),
(31, 'Lince', 10.00),
(32, 'Los Olivos', 12.00),
(33, 'Lurigancho - Chosica', 14.00),
(34, 'Lurín', 14.00),
(35, 'Magdalena del Mar', 10.00),
(36, 'Manchay', 17.00),
(37, 'Mi Perú', 12.00),
(38, 'Miraflores', 10.00),
(39, 'Pachacamac', 19.00),
(40, 'Pucusana', 28.00),
(41, 'Pueblo Libre', 10.00),
(42, 'Puente Piedra', 12.00),
(43, 'Punta Hermosa', 17.00),
(44, 'Punta Negra', 21.00),
(45, 'Retiro Sede Cercado (Av Arica 1702)', 8.00),
(46, 'Retiro Sede Gamarra (Antonio Bazo 1218)', 8.00),
(47, 'Ricardo Palma', 25.00),
(48, 'Rímac', 10.00),
(49, 'Salamanca Ate', 10.00),
(50, 'San Bartolo', 22.00),
(51, 'San Borja', 10.00),
(52, 'San Isidro', 10.00),
(53, 'San Juan de Lurigancho', 10.00),
(54, 'San Juan de Miraflores', 12.00),
(55, 'San Luis', 10.00),
(56, 'San Martín de Porres', 12.00),
(57, 'San Miguel', 10.00),
(58, 'Santa Anita', 10.00),
(59, 'Santa Clara - Ate', 12.00),
(60, 'Santa Eulalia', 25.00),
(61, 'Santa María del Mar', 25.00),
(62, 'Santa Rosa', 17.00),
(63, 'Santiago de Surco', 10.00),
(64, 'Surquillo', 10.00),
(65, 'Ventanilla', 12.00),
(66, 'Villa El Salvador', 12.00),
(67, 'Villa María del Triunfo', 12.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `emprendedores`
--

CREATE TABLE `emprendedores` (
  `id_emprendedor` int(11) NOT NULL,
  `nombre_tienda` varchar(255) DEFAULT NULL,
  `nombre_cliente` varchar(255) DEFAULT NULL,
  `rubro` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `emprendedores`
--

INSERT INTO `emprendedores` (`id_emprendedor`, `nombre_tienda`, `nombre_cliente`, `rubro`, `direccion`, `telefono`, `contrasena`, `creado_en`) VALUES
(3, 'SALAS STORE', 'LUIS SALAS', 'TEXTIL', 'AV BRASIL 2453', '944718388', '$2y$10$vR1i8FwNxBqXlQ0RL08RsOkeu/MskyD6L0Hd46I8mojWlxZmHuumK', '2025-12-09 06:04:13'),
(4, 'VARGAS', 'VELOZZI STORE', 'COSMETICOS', 'AV ARICA BREÑA', '944718389', '$2y$10$zimEuoFrIJEs4ybZB6B42.sb8X5CkV0Lkbl3GO4aTUnPMgj1YgyLS', '2025-12-10 03:59:10'),
(5, 'polos store', 'LAURA', 'TEXTIL', 'AV ARICA BREÑA', '944714565', '$2y$10$FP8nLQvC6mkV7woY13o24ue1lyplJmiHAvrIhs9FDkY78LDw274CO', '2025-12-10 05:00:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_contacto`
--

CREATE TABLE `mensajes_contacto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `telefono` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensajes_contacto`
--

INSERT INTO `mensajes_contacto` (`id`, `nombre`, `email`, `mensaje`, `fecha`, `telefono`) VALUES
(1, 'ANASTACIO', 'lpsalitasv.2000@gmail.com', 'afaff', '2025-12-09 05:52:05', '944718388'),
(2, 'ANASTACIO', 'lpsalitasv.2000@gmail.com', 'afaff', '2025-12-09 05:54:45', '944718388'),
(3, 'SARA', 'anaisabel210418@gmail.com', 'sgags', '2025-12-09 05:55:01', '999995555'),
(4, 'SARA', 'anaisabel210418@gmail.com', 'sgags', '2025-12-09 06:00:55', '999995555'),
(5, 'SARA peña', 'anaisabel210418@gmail.com', 'afaf', '2025-12-09 06:01:10', '999995555'),
(6, 'SARA peña', 'anaisabel210418@gmail.com', 'afaf', '2025-12-09 06:01:30', '999995555');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motorizados`
--

CREATE TABLE `motorizados` (
  `id_motorizado` int(11) NOT NULL,
  `nombre_completo` varchar(255) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `placa_moto` varchar(20) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `distrito_asignado` varchar(100) NOT NULL,
  `activo` enum('1','0') NOT NULL DEFAULT '1',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `motorizados`
--

INSERT INTO `motorizados` (`id_motorizado`, `nombre_completo`, `telefono`, `placa_moto`, `contrasena`, `distrito_asignado`, `activo`, `creado_en`) VALUES
(1, 'CARLOS VILLANUEVA', '978526429', 'KJL-459', '$2y$10$H990WYaQ9.CHvEvlakjzb.7LvHj1lFYbNRMooQ3Xeu/.F0kmaRR6a', 'la vi', '1', '2025-12-08 05:58:15'),
(2, 'SARA', '999999998', 'KJL-459', '$2y$10$Ofzfhus/fuhZLJhyMjgohOzzcc6S5Jx6iLhoMax5lcym6T3p//uZW', 'jesus dacid', '1', '2025-12-08 06:25:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_emprendedor` int(11) NOT NULL,
  `id_motorizado` int(11) DEFAULT NULL,
  `destinatario` varchar(120) NOT NULL,
  `tipo_pedido` varchar(40) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `distrito` varchar(80) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `detalle_pedido` text NOT NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `codigo_seguimiento` varchar(30) DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'asignado',
  `creado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_emprendedor`, `id_motorizado`, `destinatario`, `tipo_pedido`, `telefono`, `distrito`, `direccion`, `detalle_pedido`, `observaciones`, `fecha_creacion`, `codigo_seguimiento`, `estado`, `creado_en`) VALUES
(2, 0, 2, 'PEPITO LUNA', 'CONTRAENTREGA', '47895644', 'surco', 'av tukipaes', 'oso grande', 'na', '2025-12-08 06:57:47', NULL, 'asignado', '2025-12-08 02:58:39'),
(3, 0, 1, 'LAURA', 'CONTRAENTREGA', '98855555', 'breña', 'beazil', 'carros', '', '2025-12-08 07:14:49', NULL, 'entregado', '2025-12-08 02:58:39'),
(4, 0, 2, 'andrea', 'CONTRAENTREGA', '9854451', 'jesusa ara', 'adad', 'adad', 'dad', '2025-12-08 07:15:07', NULL, 'asignado', '2025-12-08 02:58:39'),
(5, 0, NULL, 'LUIS', 'CONTRAENTREGA', '978521456', 'lince', 'marquez 2456', '4 polos', '.', '2025-12-10 04:07:26', NULL, 'asignado', '2025-12-09 23:07:26'),
(6, 3, NULL, 'pablo', 'CONTRAENTREGA', '9785463', 'Pueblo Libre', 'AV BRASIL 2453', 'carros', 'depeusde las 2', '2025-12-10 04:15:41', NULL, 'asignado', '2025-12-09 23:15:41'),
(7, 3, NULL, 'LUPITA', 'CONTRAENTREGA', '985426359', 'Chaclacayo', 'LAS CALLES', 'PANETONES', 'AAA', '2025-12-10 04:22:18', NULL, 'asignado', '2025-12-09 23:22:18'),
(8, 3, NULL, 'LUPITA', 'CONTRAENTREGA', '985426359', 'Chaclacayo', 'LAS CALLES', 'PANETONES', 'AAA', '2025-12-10 04:22:34', NULL, 'asignado', '2025-12-09 23:22:34'),
(9, 3, NULL, 'cRLOS', 'CONTRAENTREGA', '9856244', 'Callao', 'GDAGG', 'GAGA', 'AG', '2025-12-10 04:25:49', NULL, 'asignado', '2025-12-09 23:25:49'),
(10, 0, NULL, 'lunita', 'CONTRAENTREGA', '785412596', 'Magdalena del Mar', 'Jr. Los Olivos 456', 'adadad', 'add', '2025-12-10 05:07:24', NULL, 'asignado', '2025-12-10 00:07:24');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id_administrador`);

--
-- Indices de la tabla `coberturas`
--
ALTER TABLE `coberturas`
  ADD PRIMARY KEY (`id_cobertura`);

--
-- Indices de la tabla `emprendedores`
--
ALTER TABLE `emprendedores`
  ADD PRIMARY KEY (`id_emprendedor`),
  ADD UNIQUE KEY `telefono` (`telefono`);

--
-- Indices de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `motorizados`
--
ALTER TABLE `motorizados`
  ADD PRIMARY KEY (`id_motorizado`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id_administrador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `coberturas`
--
ALTER TABLE `coberturas`
  MODIFY `id_cobertura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de la tabla `emprendedores`
--
ALTER TABLE `emprendedores`
  MODIFY `id_emprendedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `motorizados`
--
ALTER TABLE `motorizados`
  MODIFY `id_motorizado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
