<?php

class test_client {

    protected $t; // Target.

    public function __construct() {

        $this->t = new StdClass;

        // Setup this settings for tests
        $this->t->baseurl = 'http://dev.moodle31.fr'; // The remote Moodle url to push in.
        $this->t->wstoken = 'b7abe19e080df3cc8f49c579597c8af5'; // The service token for access.
        $this->t->filepath = ''; // Some physical location on your system.
        $this->t->idnumber = 'customlabelwstest'; // ID Number of the VR test instance.

        $this->t->uploadservice = '/webservice/upload.php';
        $this->t->service = '/webservice/rest/server.php';
    }

    public function test_get_content($lang = '') {

        if (empty($this->t->wstoken)) {
            echo "No token to proceed\n";
            return;
        }

        $params = array('wstoken' => $this->t->wstoken,
                        'wsfunction' => 'mod_customlabel_get_content',
                        'moodlewsrestformat' => 'json',
                        'cidsource' => 'idnumber',
                        'cid' => $this->t->idnumber,
                        'lang' => $lang);

        $serviceurl = $this->t->baseurl.$this->t->service;

        return $this->send($serviceurl, $params);
    }

    public function test_get_attribute($attr) {

        if (empty($this->t->wstoken)) {
            echo "No token to proceed\n";
            return;
        }

        $serviceurl = $this->t->baseurl.$this->t->service;

        $params = array('wstoken' => $this->t->wstoken,
                        'wsfunction' => 'mod_customlabel_get_attribute',
                        'moodlewsrestformat' => 'json',
                        'cidsource' => 'idnumber',
                        'cid' => $this->t->idnumber,
                        'attributekey' => $attr);

        return $this->send($serviceurl, $params);
    }

    public function test_set_attribute($key, $value) {

        if (empty($this->t->wstoken)) {
            echo "No token to proceed\n";
            return;
        }

        $serviceurl = $this->t->baseurl.$this->t->service;

        $params = array('wstoken' => $this->t->wstoken,
                        'wsfunction' => 'mod_customlabel_set_attribute',
                        'moodlewsrestformat' => 'json',
                        'cidsource' => 'idnumber',
                        'cid' => $this->t->idnumber,
                        'attributekey' => $key,
                        'value' => $value);

        return $this->send($serviceurl, $params);
    }

    public function test_refresh() {

        if (empty($this->t->wstoken)) {
            echo "No token to proceed\n";
            return;
        }

        $serviceurl = $this->t->baseurl.$this->t->service;

        $params = array('wstoken' => $this->t->wstoken,
                        'wsfunction' => 'mod_customlabel_refresh',
                        'moodlewsrestformat' => 'json',
                        'cidsource' => 'idnumber',
                        'cid' => $this->t->idnumber);

        return $this->send($serviceurl, $params);
    }

    protected function upload_file($target, $file) {

        if (empty($this->t->wstoken)) {
            echo "No token to proceed\n";
            return;
        }

        $serviceurl = $target->baseurl.$target->uploadservice;

        $params = array('token' => $target->wstoken,
                        'itemid' => 0,
                        'filearea' => 'draft');

        $ch = curl_init($uploadurl);

        $curlfile = new CURLFile($file, 'x-application/zip', 'resourcefile');
        $params['resourcefile'] = $curlfile;

        return $this->send($serviceurl, $params);
    }

    protected function commit_file($target, $draftitemid, $idnumber, $jsoninfo) {

        if (empty($this->t->wstoken)) {
            echo "No token to proceed\n";
            return;
        }

        $params = array('wstoken' => $target->wstoken,
                        'wsfunction' => 'mod_versionnedresource_commit_version',
                        'moodlewsrestformat' => 'json',
                        'vridsource' => 'idnumber',
                        'vrid' => $idnumber,
                        'draftitemid' => $draftitemid,
                        'jsoninfo' => json_encode($jsoninfo));

        $serviceurl = $target->baseurl.$target->commitservice;

        return $this->send($serviceurl, $params);
    }

    protected function send($serviceurl, $params) {
        $ch = curl_init($serviceurl);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        echo "Firing CUrl $serviceurl ... \n";
        if (!$result = curl_exec($ch)) {
            echo "CURL Error : ".curl_errno($ch).' '.curl_error($ch)."\n";
            return;
        }

        echo $result;
        if (preg_match('/EXCEPTION/', $result)) {
            echo $result;
            return;
        }

        $result = json_decode($result);
        print_r($result);
        return $result;
    }
}

// Effective test scenario

$client = new test_client();

echo "Test content\n";
print_r($client->test_get_content());
echo "\nTest attribute label class\n";
echo $client->test_get_attribute('labelclass'); // Test the special type attribute
echo "\nTest getting attribute \n";
echo $client->test_get_attribute('textcontent');
echo "\nTest getting attribute \n";
echo $client->test_get_attribute('readmorecontent');
echo "\nTest setting attribute \n";
echo $client->test_set_attribute('readmorecontent', 'This is a text update in component');
echo "\nTest setting attribute \n";
echo $client->test_set_attribute('readmorecontent', 'This is a readmore update in component');
echo "\nTest getting attribute \n";
echo $client->test_get_attribute('textcontent');
echo "\nTest getting attribute \n";
echo $client->test_get_attribute('readmorecontent');
echo "\nTest refresh \n";
echo $client->test_refresh();
echo "Test content changed\n";
echo $client->test_get_content();
