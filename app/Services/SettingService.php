<?php

namespace App\Services;

use App\Models\SettingModel;

class SettingService
{
    private SettingModel $settingsModel;

    public function __construct()
    {
        $this->settingsModel = new SettingModel();
    }

    public function loadUpdateSetting(string $key, string $value) : string
    {
        return $this->settingsModel->updateSetting($key, $value);
    }

    public function loadSettingValue(string $key) : string
    {
        return $this->settingsModel->getSettingValue($key);
    }

    public function loadUpdateSettings(array $validatedData)
    {
        foreach($validatedData as $key => $value) {
            if ($key != 'log') {
                $this->settingsModel->updateSetting($key, $value);
            }
        }

        return true;
    }

    public function loadAllSettings()
    {
        $allSettings = $this->settingsModel->getAllSettings();

        $container = [];

        foreach($allSettings as $key => $setting) {
            $container[$setting['key']] = $setting['value'];
        }

        return $container;
    }

}
