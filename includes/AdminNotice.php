<?php

namespace LicenseManagerForWooCommerce;

defined('ABSPATH') || exit;

/**
 * Set up WordPress Admin Notices.
 *
 * @version 1.1.0
 * @since 1.0.0
 */
class AdminNotice
{
    const MESSAGE_DISMISSIBLE = '<div class="notice %s is-dismissible"><p><b>License Manager</b>: %s</p></div>';
    const MESSAGE_PERMANENT   = '<div class="notice %s"><p>%s</p></div>';

    const NOTICE_ERROR   = 'notice-error';
    const NOTICE_SUCCESS = 'notice-success';
    const NOTICE_WARNING = 'notice-warning';
    const NOTICE_INFO    = 'notice-info';

    /**
     * Class constructor.
     */
    public function __construct() {
        add_action('admin_notices', array($this, 'initAdminNotices'));
    }

    public function initAdminNotices()
    {
        $this->transients();
        //$this->importLicenseKeys();
        $this->addLicenseKey();
        $this->activateLicenseKey();
        $this->deleteLicenseKey();
        $this->invalidNonce();
    }

    private function transients()
    {
        if ($error = get_transient('lmfwc_notice_error')) {
            echo sprintf(
                self::MESSAGE_DISMISSIBLE,
                self::NOTICE_ERROR,
                $error->get_error_message()
            );

            delete_transient('lmfwc_error');
        } elseif ($success = get_transient('lmfwc_notice_success')) {
            echo sprintf(
                self::MESSAGE_DISMISSIBLE,
                self::NOTICE_SUCCESS,
                $success
            );

            delete_transient('lmfwc_notice_success');
        } elseif ($warning = get_transient('lmfwc_notice_warning')) {
            echo sprintf(
                self::MESSAGE_DISMISSIBLE,
                self::NOTICE_WARNING,
                $warning
            );

            delete_transient('lmfwc_notice_warning');
        } elseif ($info = get_transient('lmfwc_notice_info')) {
            echo sprintf(
                self::MESSAGE_DISMISSIBLE,
                self::NOTICE_INFO,
                $info
            );

            delete_transient('lmfwc_notice_info');
        }
    }

    private function importLicenseKeys()
    {
        // Return if this is not related.
        if (!isset($_GET['lmfwc_import_license_keys'])) return;

        if ($_GET['lmfwc_import_license_keys'] == 'error') {
            echo sprintf(
                self::MESSAGE_DISMISSIBLE,
                self::NOTICE_ERROR,
                __('Something went wrong, no keys were added. Please try again.', 'lmfwc')
            );
        } elseif ($_GET['lmfwc_import_license_keys'] == 'true' && isset($_GET['added'])) {
            echo sprintf(
                self::MESSAGE_DISMISSIBLE,
                self::NOTICE_SUCCESS,
                sprintf(
                    __('%d key(s) have been imported successfully.', 'lmfwc'),
                    intval($_GET['added'])
                )
            );
        } elseif ($_GET['lmfwc_import_license_keys'] == 'false' && isset($_GET['rejected'])) {
            echo sprintf(
                self::MESSAGE_DISMISSIBLE,
                self::NOTICE_ERROR,
                sprintf(
                    __('Import failed. %d key(s) were not added.', 'lmfwc'),
                    intval($_GET['rejected'])
                )
            );
        } elseif ($_GET['lmfwc_import_license_keys'] == 'mixed' && isset($_GET['added']) && isset($_GET['rejected'])) {
            echo sprintf(
                self::MESSAGE_DISMISSIBLE,
                self::NOTICE_WARNING,
                sprintf(
                    __('%d key(s) have been imported and %d key(s) were not imported.', 'lmfwc'),
                    intval($_GET['added']),
                    intval($_GET['rejected'])
                )
            );
        }
    }

    private function addLicenseKey()
    {
        // Return if this is not related.
        if (!isset($_GET['lmfwc_add_license_key'])) return;

        if ($_GET['lmfwc_add_license_key'] == 'true') {
            echo sprintf(
                self::MESSAGE_DISMISSIBLE,
                self::NOTICE_SUCCESS,
                __('Your license key has been added successfully.', 'lmfwc')
            );
        } else {
            echo sprintf(
                self::MESSAGE_DISMISSIBLE,
                self::NOTICE_ERROR,
                __('Something went wrong, your key was not added. Please try again.', 'lmfwc')
            );
        }
    }

    private function activateLicenseKey()
    {
        // Return if this is not related.
        if (!isset($_GET['lmfwc_activate_license_key'])) return;

        if ($_GET['lmfwc_activate_license_key'] == 'true') {
            echo sprintf(
                self::MESSAGE_DISMISSIBLE,
                self::NOTICE_SUCCESS,
                __('Your license key has been activated successfully.', 'lmfwc')
            );
        } else {
            echo sprintf(
                self::MESSAGE_DISMISSIBLE,
                self::NOTICE_ERROR,
                __('Something went wrong, your license key was not deactivate. Please try again.', 'lmfwc')
            );
        }
    }

    private function deleteLicenseKey()
    {
        // Return if this is not related.
        if (!isset($_GET['lmfwc_delete_license_key'])) return;

        if ($_GET['lmfwc_delete_license_key'] == 'true') {
            echo sprintf(
                self::MESSAGE_DISMISSIBLE,
                self::NOTICE_SUCCESS,
                __('Your license key has been activated successfully.', 'lmfwc')
            );
        } else {
            echo sprintf(
                self::MESSAGE_DISMISSIBLE,
                self::NOTICE_ERROR,
                __('Something went wrong, your license key was not deactivate. Please try again.', 'lmfwc')
            );
        }
    }

    private function invalidNonce()
    {
        // Return if this is not related.
        if (!isset($_GET['lmfwc_nonce_status'])) return;

        if ($_GET['lmfwc_nonce_status'] == 'invalid') {
            echo sprintf(
                self::MESSAGE_DISMISSIBLE,
                self::NOTICE_ERROR,
                __('Invalid nonce! Your action was not completed.', 'lmfwc')
            );
        }
    }
    /**
     * Adds a dashboard notice to be displayed on the next page reload.
     *
     * @since 1.1.0
     *
     * @param string $level
     * @param string $message
     * @param int $code
     * @param int $duration
     */
    public static function add($level, $message, $code = -1, $duration = 60)
    {
        switch ($level) {
            case 'error':
                set_transient('lmfwc_notice_error', new \WP_Error($code, $message), $duration);
                break;
            case 'success':
                set_transient('lmfwc_notice_success', $message, $duration);
                break;
            case 'warning':
                set_transient('lmfwc_notice_warning', $message, $duration);
                break;
            case 'info':
                set_transient('lmfwc_notice_info', $message, $duration);
                break;
        }
    }
}