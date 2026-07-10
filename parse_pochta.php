<?php

declare(strict_types=1);

const SOURCE_URL = 'https://www.pochta.ru/support/database/ops';
const BASE_URL = 'https://www.pochta.ru';

const WORK_DIR = __DIR__ . '/pochta_tmp';
const ZIP_PATH = WORK_DIR . '/PIndx.zip';
const DBF_PATH = WORK_DIR . '/PIndx.dbf';
const CSV_PATH = __DIR__ . '/pochta_indexes.csv';

/**
 * Если true, выведет список полей DBF и первые 3 записи в консоль.
 */
const DEBUG = false;

/**
 * Маппинг регионов в таймзоны.
 * Для Якутии, Красноярского края, Сахалина и похожих регионов могут понадобиться исключения
 */
const REGION_TIMEZONES = [
    // UTC+2
    'Калининградская' => '+2',
    'Калининградская область' => '+2',

    // UTC+3
    'Москва' => '+3',
    'город Москва' => '+3',
    'Московская' => '+3',
    'Московская область' => '+3',

    'Санкт-Петербург' => '+3',
    'город Санкт-Петербург' => '+3',
    'Ленинградская' => '+3',
    'Ленинградская область' => '+3',

    'Севастополь' => '+3',
    'город Севастополь' => '+3',

    'Адыгея' => '+3',
    'Республика Адыгея' => '+3',

    'Архангельская' => '+3',
    'Архангельская область' => '+3',

    'Белгородская' => '+3',
    'Белгородская область' => '+3',

    'Брянская' => '+3',
    'Брянская область' => '+3',

    'Владимирская' => '+3',
    'Владимирская область' => '+3',

    'Волгоградская' => '+3',
    'Волгоградская область' => '+3',

    'Вологодская' => '+3',
    'Вологодская область' => '+3',

    'Воронежская' => '+3',
    'Воронежская область' => '+3',

    'Дагестан' => '+3',
    'Республика Дагестан' => '+3',

    'Донецкая' => '+3',
    'Донецкая Народная Республика' => '+3',
    'ДНР' => '+3',

    'Запорожская' => '+3',
    'Запорожская область' => '+3',

    'Ивановская' => '+3',
    'Ивановская область' => '+3',

    'Ингушетия' => '+3',
    'Республика Ингушетия' => '+3',

    'Кабардино-Балкарская' => '+3',
    'Кабардино-Балкарская Республика' => '+3',

    'Калмыкия' => '+3',
    'Республика Калмыкия' => '+3',

    'Калужская' => '+3',
    'Калужская область' => '+3',

    'Карачаево-Черкесская' => '+3',
    'Карачаево-Черкесская Республика' => '+3',

    'Карелия' => '+3',
    'Республика Карелия' => '+3',

    'Коми' => '+3',
    'Республика Коми' => '+3',

    'Костромская' => '+3',
    'Костромская область' => '+3',

    'Краснодарский' => '+3',
    'Краснодарский край' => '+3',

    'Крым' => '+3',
    'Республика Крым' => '+3',

    'Курская' => '+3',
    'Курская область' => '+3',

    'Липецкая' => '+3',
    'Липецкая область' => '+3',

    'Луганская' => '+3',
    'Луганская Народная Республика' => '+3',
    'ЛНР' => '+3',

    'Марий Эл' => '+3',
    'Республика Марий Эл' => '+3',

    'Мордовия' => '+3',
    'Республика Мордовия' => '+3',

    'Мурманская' => '+3',
    'Мурманская область' => '+3',

    'Ненецкий' => '+3',
    'Ненецкий автономный округ' => '+3',

    'Нижегородская' => '+3',
    'Нижегородская область' => '+3',

    'Новгородская' => '+3',
    'Новгородская область' => '+3',

    'Орловская' => '+3',
    'Орловская область' => '+3',

    'Пензенская' => '+3',
    'Пензенская область' => '+3',

    'Псковская' => '+3',
    'Псковская область' => '+3',

    'Ростовская' => '+3',
    'Ростовская область' => '+3',

    'Рязанская' => '+3',
    'Рязанская область' => '+3',

    'Северная Осетия' => '+3',
    'Республика Северная Осетия' => '+3',
    'Алания' => '+3',

    'Смоленская' => '+3',
    'Смоленская область' => '+3',

    'Ставропольский' => '+3',
    'Ставропольский край' => '+3',

    'Тамбовская' => '+3',
    'Тамбовская область' => '+3',

    'Татарстан' => '+3',
    'Республика Татарстан' => '+3',

    'Тверская' => '+3',
    'Тверская область' => '+3',

    'Тульская' => '+3',
    'Тульская область' => '+3',

    'Херсонская' => '+3',
    'Херсонская область' => '+3',

    'Чеченская' => '+3',
    'Чеченская Республика' => '+3',

    'Чувашская' => '+3',
    'Чувашская Республика' => '+3',
    'Чувашия' => '+3',

    'Ярославская' => '+3',
    'Ярославская область' => '+3',

    // UTC+4
    'Астраханская' => '+4',
    'Астраханская область' => '+4',

    'Самарская' => '+4',
    'Самарская область' => '+4',

    'Саратовская' => '+4',
    'Саратовская область' => '+4',

    'Удмуртская' => '+4',
    'Удмуртская Республика' => '+4',

    'Ульяновская' => '+4',
    'Ульяновская область' => '+4',

    // UTC+5
    'Башкортостан' => '+5',
    'Республика Башкортостан' => '+5',

    'Курганская' => '+5',
    'Курганская область' => '+5',

    'Оренбургская' => '+5',
    'Оренбургская область' => '+5',

    'Пермский' => '+5',
    'Пермский край' => '+5',

    'Свердловская' => '+5',
    'Свердловская область' => '+5',

    'Тюменская' => '+5',
    'Тюменская область' => '+5',

    'Ханты-Мансийский' => '+5',
    'Ханты-Мансийский автономный округ' => '+5',
    'Ханты-Мансийский автономный округ - Югра' => '+5',
    'Югра' => '+5',

    'Челябинская' => '+5',
    'Челябинская область' => '+5',

    'Ямало-Ненецкий' => '+5',
    'Ямало-Ненецкий автономный округ' => '+5',

    // UTC+6
    'Омская' => '+6',
    'Омская область' => '+6',

    // UTC+7
    'Алтайский' => '+7',
    'Алтайский край' => '+7',

    'Алтай' => '+7',
    'Республика Алтай' => '+7',

    'Кемеровская' => '+7',
    'Кемеровская область' => '+7',
    'Кузбасс' => '+7',

    'Красноярский' => '+7',
    'Красноярский край' => '+7',

    'Новосибирская' => '+7',
    'Новосибирская область' => '+7',

    'Томская' => '+7',
    'Томская область' => '+7',

    'Тыва' => '+7',
    'Республика Тыва' => '+7',
    'Тува' => '+7',
    'Республика Тува' => '+7',

    'Хакасия' => '+7',
    'Республика Хакасия' => '+7',

    // UTC+8
    'Бурятия' => '+8',
    'Республика Бурятия' => '+8',

    'Иркутская' => '+8',
    'Иркутская область' => '+8',

    // UTC+9
    'Амурская' => '+9',
    'Амурская область' => '+9',

    'Забайкальский' => '+9',
    'Забайкальский край' => '+9',

    'Саха' => '+9',
    'Якутия' => '+9',
    'Республика Саха' => '+9',
    'Республика Саха (Якутия)' => '+9',

    // UTC+10
    'Еврейская' => '+10',
    'Еврейская автономная область' => '+10',

    'Приморский' => '+10',
    'Приморский край' => '+10',

    'Хабаровский' => '+10',
    'Хабаровский край' => '+10',

    // UTC+11
    'Магаданская' => '+11',
    'Магаданская область' => '+11',

    'Сахалинская' => '+11',
    'Сахалинская область' => '+11',

    // UTC+12
    'Камчатский' => '+12',
    'Камчатский край' => '+12',

    'Чукотский' => '+12',
    'Чукотский автономный округ' => '+12',
];

function downloadPage(string $url): string
{
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "User-Agent: Mozilla/5.0\r\n",
            'timeout' => 60,
        ],
    ]);

    $html = file_get_contents($url, false, $context);

    if ($html === false) {
        throw new RuntimeException("Не удалось скачать страницу: {$url}");
    }

    return $html;
}


function extractArchiveDateFromPage(string $html): string
{
    // Ищем дату на странице рядом с описанием архива. 
    // вариант около слов "сформирован", "обновлен", "архив".
    $patterns = [
        '~(?:сформирован|сформирована|обновлен|обновлена|актуал[ьи]зирован|архив)[^0-9]{0,120}(\d{2}\.\d{2}\.\d{4})~iu',
        '~(\d{2}\.\d{2}\.\d{4})[^<]{0,120}(?:сформирован|сформирована|обновлен|обновлена|актуал[ьи]зирован|архив)~iu',
        '~(\d{4}-\d{2}-\d{2})~u',
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $html, $match)) {
            return $match[1];
        }
    }

    return '';
}

function startsWith(string $haystack, string $needle): bool
{
    return substr($haystack, 0, strlen($needle)) === $needle;
}

function findZipUrl(string $html): string
{
    preg_match_all('~href=["\']([^"\']+\.zip)["\']~iu', $html, $matches);

    foreach ($matches[1] ?? [] as $url) {
        if (stripos($url, 'pindx') !== false) {
            return startsWith($url, 'http') ? $url : BASE_URL . $url;
        }
    }

    // Иногда ссылка лежит не в href, а просто в JS/JSON.
    if (preg_match('~(/assets[^"\']*(?:pindx|PIndx)[^"\']*\.zip)~iu', $html, $match)) {
        return BASE_URL . $match[1];
    }

    // Более грубый запасной вариант: первый zip из /assets.
    if (preg_match('~(/assets[^"\']+\.zip)~iu', $html, $match)) {
        return BASE_URL . $match[1];
    }

    throw new RuntimeException('Не нашёл ссылку на PIndx.zip на странице Почты');
}

function downloadFile(string $url, string $path): void
{
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => "User-Agent: Mozilla/5.0\r\n",
            'timeout' => 120,
        ],
    ]);

    $data = file_get_contents($url, false, $context);

    if ($data === false) {
        throw new RuntimeException("Не удалось скачать файл: {$url}");
    }

    file_put_contents($path, $data);
}

function extractDbf(string $zipPath, string $dbfPath): void
{
    $zip = new ZipArchive();

    if ($zip->open($zipPath) !== true) {
        throw new RuntimeException("Не удалось открыть ZIP: {$zipPath}");
    }

    $dbfIndex = null;

    for ($i = 0; $i < $zip->numFiles; $i++) {
        $name = $zip->getNameIndex($i);

        if (strtolower(pathinfo($name, PATHINFO_EXTENSION)) === 'dbf') {
            $dbfIndex = $i;
            break;
        }
    }

    if ($dbfIndex === null) {
        $zip->close();
        throw new RuntimeException('В архиве не найден DBF-файл');
    }

    $content = $zip->getFromIndex($dbfIndex);

    if ($content === false) {
        $zip->close();
        throw new RuntimeException('Не удалось прочитать DBF из архива');
    }

    file_put_contents($dbfPath, $content);
    $zip->close();
}

function readUInt16(string $data, int $offset): int
{
    $part = substr($data, $offset, 2);
    if (strlen($part) !== 2) {
        throw new RuntimeException("Не удалось прочитать UInt16 на offset={$offset}");
    }

    return unpack('v', $part)[1];
}

function readUInt32(string $data, int $offset): int
{
    $part = substr($data, $offset, 4);
    if (strlen($part) !== 4) {
        throw new RuntimeException("Не удалось прочитать UInt32 на offset={$offset}");
    }

    return unpack('V', $part)[1];
}


function readDbfLastUpdateDate(string $data): string
{
    if (strlen($data) < 4) {
        return '';
    }

    // В DBF дата последнего обновления лежит в байтах 1,2,3:
    // год от 1900, месяц, день. Спасибо древним форматам за квест.
    $year = ord($data[1]) + 1900;
    $month = ord($data[2]);
    $day = ord($data[3]);

    if (!checkdate($month, $day, $year)) {
        return '';
    }

    return sprintf('%04d-%02d-%02d', $year, $month, $day);
}

function decodeDbfString(string $value): string
{
    $value = trim($value);

    if ($value === '') {
        return '';
    }

    // У Почты обычно CP866. Если внезапно будет другая кодировка, это место первое под подозрением.
    $converted = iconv('CP866', 'UTF-8//IGNORE', $value);

    return $converted !== false ? trim($converted) : trim($value);
}

function strContainsMb(string $haystack, string $needle): bool
{
    if (function_exists('mb_stripos')) {
        return mb_stripos($haystack, $needle) !== false;
    }

    return stripos($haystack, $needle) !== false;
}

function detectTimezone(string $region): string
{
    foreach (REGION_TIMEZONES as $part => $timezone) {
        if (strContainsMb($region, $part)) {
            return $timezone;
        }
    }

    return '';
}

function normalizeFieldName(string $name): string
{
    return strtoupper(trim($name));
}

function getField(array $row, string $name): string
{
    $key = normalizeFieldName($name);
    return $row[$key] ?? '';
}

function detectSettlement(array $row): string
{
    // В справочнике Почты населенный пункт может быть в разных полях.
    // Берем первое непустое, иначе используем название ОПС.
    $candidates = [
        'CITY',
        'CITY1',
        'OPSNAME',
    ];

    foreach ($candidates as $field) {
        $value = getField($row, $field);
        if ($value !== '') {
            return $value;
        }
    }

    return '';
}

function csvWrite($handle, array $row): void
{
    // Пятый параметр нужен для PHP 8.4+, иначе Deprecated.
    fputcsv($handle, $row, ';', '"', '');
}

function parseDbfToCsv(string $dbfPath, string $csvPath): array
{
    $data = file_get_contents($dbfPath);

    if ($data === false) {
        throw new RuntimeException("Не удалось прочитать DBF: {$dbfPath}");
    }

    if (strlen($data) < 32) {
        throw new RuntimeException('DBF слишком короткий или поврежден');
    }

    $recordsCount = readUInt32($data, 4);
    $headerLength = readUInt16($data, 8);
    $recordLength = readUInt16($data, 10);
    $dbfLastUpdateDate = readDbfLastUpdateDate($data);

    if ($headerLength <= 32 || $recordLength <= 1) {
        throw new RuntimeException("Странный DBF headerLength={$headerLength}, recordLength={$recordLength}");
    }

    $fields = [];
    $offset = 32;

    while ($offset < $headerLength) {
        $firstByte = ord($data[$offset]);

        if ($firstByte === 0x0D) {
            break;
        }

        $fieldRaw = substr($data, $offset, 32);

        if (strlen($fieldRaw) < 32) {
            break;
        }

        $name = rtrim(substr($fieldRaw, 0, 11), "\0 ");
        $type = $fieldRaw[11];
        $length = ord($fieldRaw[16]);

        if ($name !== '' && $length > 0) {
            $fields[] = [
                'name' => normalizeFieldName($name),
                'type' => $type,
                'length' => $length,
            ];
        }

        $offset += 32;
    }

    if (!$fields) {
        throw new RuntimeException('Не удалось прочитать поля DBF');
    }

    if (DEBUG) {
        echo "DBF: records={$recordsCount}, headerLength={$headerLength}, recordLength={$recordLength}, lastUpdate={$dbfLastUpdateDate}\n";
        echo "Поля DBF:\n";
        foreach ($fields as $field) {
            echo "- {$field['name']} | type={$field['type']} | length={$field['length']}\n";
        }
    }

    $csv = fopen($csvPath, 'w');

    if (!$csv) {
        throw new RuntimeException("Не удалось создать CSV: {$csvPath}");
    }

    csvWrite($csv, [
        'postal_index',
        'region',
        'area',
        'city',
        'city1',
        'settlement',
        'ops_name',
        'ops_type',
        'ops_subm',
        'act_date',
        'index_old',
        'timezone',
    ]);

    $written = 0;
    $debugShown = 0;
    $missingTimezoneRegions = [];

    for ($i = 0; $i < $recordsCount; $i++) {
        $recordOffset = $headerLength + ($i * $recordLength);
        $record = substr($data, $recordOffset, $recordLength);

        if ($record === '' || strlen($record) < $recordLength) {
            continue;
        }

        // Первый байт: пробел = активная запись, * = удаленная.
        if ($record[0] === '*') {
            continue;
        }

        $row = [];
        $cursor = 1;

        foreach ($fields as $field) {
            $rawValue = substr($record, $cursor, $field['length']);
            $cursor += $field['length'];

            $row[$field['name']] = decodeDbfString($rawValue);
        }

        if (DEBUG && $debugShown < 3) {
            echo "Пример записи #" . ($debugShown + 1) . ":\n";
            print_r($row);
            $debugShown++;
        }

        $region = getField($row, 'REGION');
        $settlement = detectSettlement($row);
        $timezone = detectTimezone($region);

        if ($region !== '' && $timezone === '') {
            if (!isset($missingTimezoneRegions[$region])) {
                $missingTimezoneRegions[$region] = 0;
            }

            $missingTimezoneRegions[$region]++;
        }

        csvWrite($csv, [
            getField($row, 'INDEX'),
            $region,
            getField($row, 'AREA'),
            getField($row, 'CITY'),
            getField($row, 'CITY1'),
            $settlement,
            getField($row, 'OPSNAME'),
            getField($row, 'OPSTYPE'),
            getField($row, 'OPSSUBM'),
            getField($row, 'ACTDATE'),
            getField($row, 'INDEXOLD'),
            $timezone,
        ]);

        $written++;
    }

    fclose($csv);

    ksort($missingTimezoneRegions, SORT_NATURAL | SORT_FLAG_CASE);

    return [
        'written' => $written,
        'dbf_last_update_date' => $dbfLastUpdateDate,
        'missing_timezone_regions' => $missingTimezoneRegions,
    ];
}

function main(): void
{
    if (!extension_loaded('zip')) {
        throw new RuntimeException('Не установлено PHP-расширение zip');
    }

    if (!function_exists('iconv')) {
        throw new RuntimeException('Не доступен iconv. Он нужен для конвертации CP866 -> UTF-8');
    }

    if (!is_dir(WORK_DIR) && !mkdir(WORK_DIR, 0775, true) && !is_dir(WORK_DIR)) {
        throw new RuntimeException('Не удалось создать папку: ' . WORK_DIR);
    }

    echo "Скачиваю страницу Почты...\n";
    $html = downloadPage(SOURCE_URL);
    $pageArchiveDate = extractArchiveDateFromPage($html);

    if ($pageArchiveDate !== '') {
        echo "Дата архива на странице Почты: {$pageArchiveDate}\n";
    } else {
        echo "Дата архива на странице Почты: не найдена\n";
    }

    echo "Ищу ссылку на архив...\n";
    $zipUrl = findZipUrl($html);

    echo "Архив: {$zipUrl}\n";

    echo "Скачиваю ZIP...\n";
    downloadFile($zipUrl, ZIP_PATH);

    echo "Распаковываю DBF...\n";
    extractDbf(ZIP_PATH, DBF_PATH);

    echo "Парсю DBF и пишу CSV...\n";
    $result = parseDbfToCsv(DBF_PATH, CSV_PATH);

    echo "Готово.\n";
    echo "Файл: " . CSV_PATH . "\n";
    echo "Строк: " . $result['written'] . "\n";
    echo "Дата архива на странице  Почты: " . ($pageArchiveDate !== '' ? $pageArchiveDate : 'не найдена') . "\n";
    echo "Дата обновления внутри DBF: " . ($result['dbf_last_update_date'] !== '' ? $result['dbf_last_update_date'] : 'не найдена') . "\n";

    if (!empty($result['missing_timezone_regions'])) {
        echo "\nРегионы, по которым не найдена таймзона:\n";

        foreach ($result['missing_timezone_regions'] as $region => $count) {
            echo "- {$region}: {$count} строк\n";
        }
    } else {
        echo "\nТаймзона найдена для всех непустых регионов. Чудо, но задокументируем.\n";
    }
}

try {
    main();
} catch (Throwable $e) {
    fwrite(STDERR, "Ошибка: " . $e->getMessage() . PHP_EOL);
    exit(1);
}
