<?php

class URL
{
    private const LINK_ACTIVE_DURATION = 30 * 24 * 60 * 60; // месяц в секундах

    private object $db;

    private string $date_left_margin;
    private string $date_right_margin;

    public string $error = '';
    public string $url_hash;
    public string $url_short;
    public string $url_long;

    public function __construct()
    {
        $this->db = new DB;
    }

    public function __destruct()
    {
        $this->db->mysqli->close();
    }

    public function processInputURL( $url_string )
    {
        if ( !empty ( $validation_error = Utility::validateInput( $url_string ) ) )
        {
            $this->error .= $validation_error;
        }
        else // ввели корректный URL -- начинаем обработку
        {
            // проверяем наличие введённого длинного URL в базе
            if ( $url_row = $this->findRecordByLongURL( $url_string ) ) // длинный URL в базе существует
            {
                $this->url_long = $url_row['url_long']; // забираем длинный URL из базы
                if ( $result = $this->updateCreatedDate( $this->url_long ) == true ) // обновляем дату отсчёта 30 дней
                {
                    $this->url_short = Utility::buildShortURLFromHash( $url_row['url_hash'] ); // генерируем ссылку из хэша в базе
                }
                else
                {
                    $this->error .= $result;
                }
            }
            else // в базе нет соотв записей
            {
                $this->url_hash = $this->generateUniqueHash( $url_string ); // создаём новый уникальный хэш

                if ( $result = $this->insertNewRecord( $this->url_hash, $url_string ) == true ) // добавляем запись в базу
                {
                    $this->url_short = Utility::buildShortURLFromHash( $this->url_hash ); // генерируем ссылку из созданного хэша
                    $this->url_long = $url_string; // используем введённый URL
                }
                else
                {
                    $this->error .= $result;
                }
            }
        }
    }

    public function findAllRecords() {
        $sql = "SELECT * FROM main";
        return $result = $this->db->mysqli->query( $sql );
    }
    
    public function findActiveRecordByHash( $url_hash )
    {
        $this->date_left_margin = date( "Ymd", time() - self::LINK_ACTIVE_DURATION ); // ограничение - один месяц назад от сегодняшней даты 
        $this->date_right_margin = date( "Ymd" );
        
        $sql = "SELECT * FROM main ";
        $sql .= "WHERE url_hash='" . $url_hash . "' ";
        $sql .= "AND created BETWEEN '" . $this->date_left_margin . "' and '" . $this->date_right_margin . "'"; // проверка даты

        $result = $this->db->mysqli->query( $sql );

        if ( $result->num_rows > 0 ) {  // найден короткий URL
            return $url_row = $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function findRecordByLongURL( $url_long )
    {
        $sql = "SELECT id, url_hash, url_long FROM main ";
        $sql .= "WHERE url_long='" . $url_long . "'";

        $result = $this->db->mysqli->query( $sql );

        if ( $result->num_rows > 0 ) {  // найден длинный URL
            return $url_row = $result->fetch_assoc();
        } else {
            return false;
        }
    }
    
    public function updateCount( $url_row )
    {
        $count = $url_row['count'] + 1;
        $sql = "UPDATE main SET ";
        $sql .= "count='" . $count . "' ";
        $sql .= "WHERE id='" . $url_row['id'] . "' ";
        $sql .= "LIMIT 1";
        $result = $this->db->mysqli->query( $sql );
    }


    public function updateCreatedDate( $url_long )
    {
        $sql = "UPDATE main SET ";
        $sql .= "created='" . date("Y-m-d") . "' ";
        $sql .= "WHERE url_long='" . $url_long . "' ";
        $sql .= "LIMIT 1";

        $result = $this->db->mysqli->query( $sql );

        if ( $result ) {
            return true;
        } else {
            return $this->db->mysqli->error;
        }
    }

    public function generateUniqueHash( $url_long ) {
        $url_hash = hash( 'crc32', $url_long );
        $sql = "SELECT url_hash FROM main ";
        $sql .= "WHERE url_hash='" . $url_hash . "'";

        $result = $this->db->mysqli->query( $sql );

        if ( $result->num_rows > 0 ) { // в базе уже есть аналогичный хэш
            return $this->generateUniqueHash( $url_long );
        } else {
            return $url_hash;
        }
    }

    public function insertNewRecord( $url_hash, $url_long )
    {
        $date = date( "Ymd" );
        $sql = "INSERT INTO main ";
        $sql .= "(url_hash, url_long, count, created) ";
        $sql .= "VALUES ('" . $url_hash . "', ";
        $sql .= "'" . $url_long . "', ";
        $sql .= "'0', ";
        $sql .= "'" . $date . "'";
        $sql .= ")";
        
        $result = $this->db->mysqli->query( $sql );

        if ( $result ) {
            return true ;
        } else {
            return $this->db->mysqli->error;
        }
    }
}


?>