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
// app/models/Lead.php

class Lead
{
    protected $conn;
    protected $table = 'phpcrm_leads';

    public function __construct($conn)
    {
        $this->conn = $conn; // mysqli object
    }

    public function all(): array
    {
        $sql = "SELECT id, name, email, phone, company_name, lead_source, status, created_at
                FROM {$this->table}
                ORDER BY id DESC";

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
        $sql = "INSERT INTO {$this->table}
                (name, email, phone, company_name, lead_source, status)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $name         = $data['name'];
        $email        = $data['email'] ?? null;
        $phone        = $data['phone'] ?? null;
        $company_name = $data['company_name'] ?? null;
        $lead_source  = $data['lead_source'] ?? null;
        $status       = $data['status'] ?? 'new';

        $stmt->bind_param(
            "ssssss",
            $name,
            $email,
            $phone,
            $company_name,
            $lead_source,
            $status
        );

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table}
                SET name = ?,
                    email = ?,
                    phone = ?,
                    company_name = ?,
                    lead_source = ?,
                    status = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $name         = $data['name'];
        $email        = $data['email'] ?? null;
        $phone        = $data['phone'] ?? null;
        $company_name = $data['company_name'] ?? null;
        $lead_source  = $data['lead_source'] ?? null;
        $status       = $data['status'] ?? 'new';

        // s = string, i = integer
        $stmt->bind_param(
            "ssssssi",
            $name,
            $email,
            $phone,
            $company_name,
            $lead_source,
            $status,
            $id
        );

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }
}
