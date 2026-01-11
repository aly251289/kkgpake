<?php
/**
 * A helper function for executing prepared statements.
 *
 * @param mysqli $koneksi The database connection object.
 * @param string $sql The SQL query with placeholders (?).
 * @param string $types A string containing the types of the parameters (e.g., "iss" for integer, string, string).
 * @param array $params An array of parameters to bind to the query.
 * @return mysqli_result|bool The result of the query, or false on failure.
 */
function db_query($koneksi, $sql, $types = '', $params = []) {
    $stmt = mysqli_prepare($koneksi, $sql);
    if ($stmt === false) {
        // Handle error, e.g., log it
        error_log('mysqli_prepare failed: ' . mysqli_error($koneksi));
        return false;
    }

    if ($types != '' && count($params) > 0) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    if (!mysqli_stmt_execute($stmt)) {
        // Handle error
        error_log('mysqli_stmt_execute failed: ' . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return false;
    }

    // For SELECT queries, return the result set.
    // For INSERT, UPDATE, DELETE, `mysqli_stmt_get_result` returns false.
    // In those cases, we should return true to indicate success.
    $result = mysqli_stmt_get_result($stmt);
    if ($result === false) {
        // This is not a SELECT query, so return true for success.
        $return_value = mysqli_stmt_affected_rows($stmt) > -1;
        mysqli_stmt_close($stmt);
        return $return_value;
    }

    mysqli_stmt_close($stmt);
    return $result;
}
