<?php
/**
 * Script for publishing release on github
 */
require_once dirname(__FILE__).'/config.php';

try {
    $release = new Release($baseApiUrl,$github_user,$github_password);
    $release->setReleaseTag($tag);

    if (!$release->isTagExists()) {
        $release->create();
        $release->addAttachment(file_get_contents($file));
    }

} catch ( Exception $e ) {
    echo 'Erreur : '.$e->getMessage();
}

class Release {

    /** @var  string */
    protected $releaseTag;

    /** @var string  */
    protected $releaseTagAllowedPattern = '^[1\.[0-9]{1,2}\.[0-9]{1,}';

    /** @var  string */
    protected $baseApiUrl;

    /** @var  string */
    protected $githubUser;

    /** @var  string */
    protected $githubPassword;

    public function __construct($baseApiUrl)
    {
        $this->baseApiUrl = $baseApiUrl;
    }

    /**
     * @return array
     */
    protected function getCurlOptions()
    {
        return array(
            CURLOPT_USERAGENT => $this->githubUser,
            CURLOPT_USERNAME => $this->githubUser,
            CURLOPT_PASSWORD => $this->githubPassword,
            CURLOPT_RETURNTRANSFER => true, //Response in variable
        );
    }

    /**
     * @return mixed
     */
    public function getReleaseTag()
    {
        return $this->releaseTag;
    }

    /**
     * @param $releaseTag
     * @return $this
     * @throws Exception
     */
    public function setReleaseTag($releaseTag)
    {
        if ( preg_match('#'.$this->releaseTagAllowedPattern.'#',$releaseTag)) {
            $this->releaseTag = $releaseTag;
            return $this;
        } else {
            throw new Exception("Invalid tag pattern");
        }
    }

    /**
     * @return bool
     */
    public function isTagExists()
    {

        $ch                = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseApiUrl.'releases/tags/'.$this->releaseTag);
        curl_setopt_array($this->getCurlOptions());

        $response = curl_exec($ch);
        $info    = curl_getinfo($ch);
        curl_close($ch);

        //It the tag already exists, end of the script
        if ($info['http_code'] == 200) {
            return true;
        }

        return false;
    }

    public function create(){

        echo "Creation of the release \n";

        $releaseDatas = array(
            "tag_name" => $this->getReleaseTag(),
            "target_commit" => 'master',
            "name" => $this->getReleaseTag(),
            "body" => "Release of version ".$this->getReleaseTag()." see changelog.txt for details",
            //Passer à true pour debug
            "draft" => false,
            "prerelease" => false,
        );

        $curlDraft = curl_init();
        curl_setopt_array($curlDraft, $this->getCurlOptions());
        curl_setopt($curlDraft, CURLOPT_URL, $this->baseApiUrl.'releases');
        curl_setopt($curlDraft, CURLOPT_POSTFIELDS, json_encode($releaseDatas));

        $draftExec = curl_exec($curlDraft);
        $draftInfo = curl_getinfo($curlDraft);

        if ($draftInfo['http_code'] == '201') {
            echo "Release created with success \n";
        } else {
            exit("Error during the creation of the release \n");
        }
        curl_close($curlDraft);

        //Traitement de la réponse
        $draftResponse  = json_decode($draftExec);
        $assetUploadUrl = str_replace('{?name,label}', '', $draftResponse->upload_url);

    }


    public function addAttachment()
    {

        echo "Add zip archive to release \n";
//Ajout de la pièce jointe à la release
        $curlUpload = curl_init();
        curl_setopt_array($curlUpload, $this->getCurlOptions());
        curl_setopt($curlUpload, CURLOPT_URL,
            $assetUploadUrl.'?name='.urlencode('eicaptcha.zip'));
        curl_setopt($curlUpload, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/zip'
            )
        );
        curl_setopt($curlUpload, CURLOPT_POSTFIELDS, file_get_contents('eicaptcha.zip'));
        $uploadExec = curl_exec($curlUpload);
        $uploadInfo = curl_getinfo($curlUpload);
        curl_close($curlUpload);

        echo "The relase is published on github \n";

    }

}

