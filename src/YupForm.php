<?php

namespace YupForms;

use Illuminate\Database\Eloquent\Model;

class YupForm extends Model
{
    /**
     * Form status
     */
    const STATUS_ACTIVE = 1;

    protected $table = 'yup_forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'public_id', 'name', 'description', 'status', 'submissions', 'host', 'recaptcha'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * Form entries
     */
    public function yupFormData()
    {
        return $this->hasMany('\YupForms\YupFormData', 'form_id', 'id');
    }

    /**
     * Create yupform
     *
     * @param array $data
     * @return $this
     */
    public function store(array $data)
    {
        $publicId = $this->generatePublicId();

        $yf = $this->create([
            'public_id' => $publicId,
            'name'        => $data['name'],
            'description' => $data['description'],
        ]);

        return $yf;
    }

    /**
     * Saves form submission
     *
     * @param $data
     * @param $server
     * @param int $flag
     * @param null $note
     * @return mixed
     */
    public function storeSubmission($data, $server, $flag = 0, $note = null)
    {
        $yupFormData = new YupFormData;

        $newYupFormData = $yupFormData->create([
            'form_id' => $this->id,
            'flagged' => $flag,
            'data'    => $data,
            'server'  => $server,
            'note'    => $note,
        ]);

        $this->addSubmission(1);

        return $newYupFormData;
    }

    /**
     * Returns form submissions as csv
     *
     * @param bool $withHeaders
     * @return string
     */
    public function yupFormDataCsv($withHeaders = true)
    {
        $first = true;
        $csvString = '';

        $yupFormData = $this->yupFormData;

        foreach ($yupFormData as $data) {
            if ($first) {
                $csvString = 'date, flagged, '
                    . implode(',', array_keys($data->data))
                    //. implode(',', array_keys($data->server))
                ;
                $first = false;
            }

            $csvString .= "\n"
                . $data->created_at
                . ', ' . $data->flagged
                . ', ' . implode(',', $data->data)
                //. ', ' . implode(',', $data->server)
            ;
        }

        return $csvString;
    }

    /**
     * Delete YupForm and all submissions
     *
     */
    public function remove()
    {

    }

    /**
     *  Unique code for public id is handled here.
     *
     */
    public function generatePublicId()
    {
        do {
            $newId = mt_rand(1000000, 9999999);

        } while ($this->publicIdExists($newId));

        return $newId;
    }

    /**
     *  Checks if public Id exists
     *
     * @param $publicId
     * @return bool
     */
    public static function publicIdExists($publicId)
    {
        $count = self::where('public_id', $publicId)
            ->count()
        ;

        return $count > 0;
    }

    /**
     * Returns the yupform by public id
     *
     * @param $publicId
     * @return YupForm|null
     */
    public function findByPublicId($publicId)
    {
        $collection = self::where('public_id', $publicId)
            ->get();

        return $collection->first();
    }

    /**
     * Adds amount of submissions to form submission count
     *
     * @param int $cnt
     */
    public function addSubmission($amount = 1)
    {
        $this->submissions += $amount;
        $this->save();
    }

    /**
     * Checks if form has host restriction
     */
    public function hasHost()
    {
        return !empty($this->host);
    }

    /**
     * Returns the host name clean of scheme or path
     *
     * @return string|null
     */
    private function cleanUrlHost($url)
    {
        $host = null;
        $parsed = parse_url($url);

        if (isset($parsed['host'])) {
            //properly structured url
            $host = $parsed['host'];

        } else if (isset($parsed['path'])) {
            //improperly structure url
            //if no scheme will be in the path. remove any unintended path
            if ($slashPos = strpos($parsed['path'], '/')) {
                $host = substr($parsed['path'], 0, $slashPos);
            } else {
                $host = $parsed['path'];
            }
        }

        return $host;
    }

    /**
     * Checks if submitUrl passes host restriction
     *
     * @param $submitUrl
     * @return bool
     */
    public function isValidHost($submitUrl)
    {
        $valid = false;

        if ($this->hasHost()) {
            $validHost = $this->cleanUrlHost($this->host);
            $submitHost = $this->cleanUrlHost($submitUrl);
            $valid = strcmp($validHost, $submitHost) == 0;

        } else {
            //no host means verification is not required
            $valid = true;
        }

        return $valid;
    }

    /**
     * Returns if form status is active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }
}
