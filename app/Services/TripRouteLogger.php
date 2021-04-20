<?php

namespace App\Services;

use App\Models\Trips as Trip;

/**
 * This is used to log a trip's route while it's happening, then to upload it
 * to a more permanent storage once the trip is finished.
 *
 * Performance is a key consideration here, since this class is used every time
 * a driver pings us with their location during a trip.
 */
class TripRouteLogger {

    const GLUE_STR = ';';
    const LOCAL_STORAGE_PATH = 'trip_routes';
    const PERMANENT_STORAGE_PATH = 'trip_routes';

    private $trip;

    /**
     * @param Trip $trip
     */
    public function __construct(Trip $trip) {
        if (!$trip->id) {
            throw new \Exception('This trip has not been saved yet');
        }

        $this->trip = $trip;
    }

    /**
     * Append a location to the trip's route file.
     *
     * If the trip route file does not exist yet, this will create it.
     *
     * @param mixed $latitude
     * @param mixed $longitude
     */
    public function append($latitude, $longitude) {
        $this->ensureLocalPath();
        file_put_contents(
            $this->getLocalPath(),
            static::formatLine([time(), $latitude, $longitude]),
            FILE_APPEND | LOCK_EX
        );
    }

    /**
     * Puts the trip route data in a more friendly format and moves it to
     * a more permanent storage location.
     */
    public function complete() {
        $storage_path = static::PERMANENT_STORAGE_PATH . '/' . $this->trip->id . '.json';

        if (\Storage::exists($storage_path)) {
            return;
        }

        \Storage::put($storage_path, json_encode($this->getLocalData(), JSON_PRETTY_PRINT));
        @unlink($this->getLocalPath());
    }

    /**
     * Reads the data from the local trip route storage file and returns it as
     * an an array of location checkin points.
     *
     * @return array
     */
    protected function getLocalData() {
        if (!is_file($this->getLocalPath())) {
            throw new \Exception('Trip route file not found!');
        }

        $handle = fopen($this->getLocalPath(), 'r');
        while (($line = fgets($handle)) !== false) {
            list($timestamp, $latitude, $longitude) = explode(static::GLUE_STR, trim($line));
            $data[] = compact('timestamp', 'latitude', 'longitude');
        }
        if (!feof($handle)) {
            throw new \Exception('Unexpected error reading trip route file');
        }

        return $data;
    }

    /**
     * Returns the file path for the trip's local route storage file.
     */
    protected function getLocalPath() {
        return storage_path() . '/' . static::LOCAL_STORAGE_PATH . '/' . $this->trip->id . '.txt';
    }

    /**
     * Makes sure that the directory that we storage the trip route files in
     * locally exists and creates it if it doesn't.
     */
    protected function ensureLocalPath() {
        $storage_dir = dirname($this->getLocalPath());
        if (is_dir($storage_dir)) {
            return;
        }

        if (!mkdir($storage_dir, 0755, true)) {
            throw new \Exception('Failed to create local trip routes directory');
        }
    }

    protected static function formatLine(array $data) {
        return implode(static::GLUE_STR, $data) . "\n";
    }
}
