# Changelog

All notable changes to this project will be documented in this file.

## 1.0.0 - 2026-09-08

First release.

WooCommerce REST API v3 client for Laravel, built on Laravel's HTTP client with HTTP Basic (consumer key/secret) auth:

- Products: CRUD plus variations, categories and tags
- Orders: CRUD plus order notes and refunds
- Customers and coupons: CRUD (customers always delete permanently, with optional `reassign`)
- Webhooks, tax rates, payment gateways and shipping zones
- Reports: sales, top sellers, system status
- `batch()` for any collection with a `/batch` endpoint
- Publishable config (store URL, API keys, REST namespace) and `Woocommerce` facade
- Laravel Boost guidelines and development skill

## [Unreleased]
