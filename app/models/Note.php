<?php
/* 
=======================================================================
 PHPCRM — Open Source CRM Software
 Version: 8.1
 License: MIT Open Source License
 Developed & Maintained By: PHPCRM (https://www.phpcrm.com)
 
 Description:
 PHPCRM is an open-source Customer Relationship Management system 
 designed for businesses of all sizes to manage Leads, Customers, 
 Follow-ups, Tasks, Notes and Sales Operations with simplicity and speed.

 Features Included in Open Source Edition:
 - Customer Management
 - Lead Management
 - Tasks / Follow-ups
 - Notes & Activity Tracking
 - Dashboard with KPIs
 - Change Password & Secure Login

 This software is open for modification and extension. 
 You are free to customize, improve and commercially use it without fees
 as long as copyright notice remains preserved.

 Community Contribution:
 We welcome developers to contribute improvements, fixes and new modules.
 Visit our website for documentation, updates and support.

 Website: https://www.phpcrm.com
 Last Update: 29-11-2025
=======================================================================
*/
// app/models/Note.php

class Note
{
    protected $conn;
    protected $table = 'phpcrm_notes';

    public function __construct($conn)
    {
        $this->conn = $conn; // mysqli object
    }

    public function all(): array
    {
        $sql = "SELECT n.*, c.name AS customer_name
                FROM {$this->table} n
                LEFT JOIN phpcrm_customers c ON c.id = n.customer_id
                ORDER BY n.created_at DESC";

        $result = $this->conn->query($sql);

        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $result->free();
        }

        return $rows;
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row    = $result->fetch_assoc();
        $stmt->close();

        return $row ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table} (customer_id, note, added_by)
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $customer_id = $data['customer_id'];
        $noteText    = $data['note'];
        $added_by    = $data['added_by'] ?? null;

        $stmt->bind_param("isi", $customer_id, $noteText, $added_by);

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
