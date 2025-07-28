<?php
namespace NirjharLo\Cgss\Models;

use wpdb;

/**
 * Simple model for cgss_insight table.
 */
class Insight
{
    /** @var wpdb */
    protected $db;

    /** @var string */
    protected $table;

    public function __construct(?wpdb $db = null)
    {
        global $wpdb;
        $this->db = $db ?: $wpdb;
        $this->table = $this->db->prefix . 'cgss_insight';
    }

    /**
     * Get all rows from the insight table.
     */
    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->get_results($sql, ARRAY_A);
    }

    /**
     * Count rows in the insight table.
     */
    public function count(): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        return (int) $this->db->get_var($sql);
    }

    /**
     * Insert an insight row.
     */
    public function insert(string $item, string $remark, int $id): bool
    {
        $result = $this->db->insert(
            $this->table,
            ['ID' => $id, 'item' => $item, 'remark' => $remark],
            ['%d', '%s', '%s']
        );
        return $result !== false;
    }

    /**
     * Update remark value by row id.
     */
    public function updateRemark(int $id, string $remark): int
    {
        return $this->db->update(
            $this->table,
            ['remark' => $remark],
            ['ID' => $id],
            ['%s'],
            ['%d']
        );
    }
}
