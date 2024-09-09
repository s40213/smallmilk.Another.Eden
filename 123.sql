-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2022-05-11 13:05:53
-- 服务器版本： 5.7.34-log
-- PHP 版本： 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `www_ziyiz_cn`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `user` text NOT NULL,
  `pass` text NOT NULL,
  `token` text NOT NULL,
  `rc4` text NOT NULL,
  `gg` text NOT NULL,
  `bb` text NOT NULL,
  `gxnr` text NOT NULL,
  `lj` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`id`, `user`, `pass`, `token`, `rc4`, `gg`, `bb`, `gxnr`, `lj`) VALUES
(1, 'admin', '123456', '12345678', '12345678', '默认公告', '1', '更新了个寂寞1', '#');

-- --------------------------------------------------------

--
-- 表的结构 `imei`
--

CREATE TABLE `imei` (
  `id` int(11) NOT NULL,
  `imei` text NOT NULL,
  `km` text NOT NULL,
  `time` text NOT NULL,
  `regtime` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `imei`
--

INSERT INTO `imei` (`id`, `imei`, `km`, `time`, `regtime`) VALUES
(1, '1322906603561652162052', 'ijnyAEGJMUWX379', '1652248043', '2022-05-11');

-- --------------------------------------------------------

--
-- 表的结构 `km`
--

CREATE TABLE `km` (
  `id` int(11) NOT NULL,
  `km` text NOT NULL,
  `time` text NOT NULL,
  `time2` text NOT NULL,
  `isyong` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `km`
--

INSERT INTO `km` (`id`, `km`, `time`, `time2`, `isyong`) VALUES
(34, 'ijnyAEGJMUWX379', '3600', '1652248043', 1);

--
-- 转储表的索引
--

--
-- 表的索引 `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `imei`
--
ALTER TABLE `imei`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `km`
--
ALTER TABLE `km`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `imei`
--
ALTER TABLE `imei`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `km`
--
ALTER TABLE `km`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
