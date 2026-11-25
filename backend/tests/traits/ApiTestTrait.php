<?php
namespace Tests\traits;
trait ApiTestTrait
{
    public function assertApiResponse(Array $actualData)
    {
        $this->assertApiSuccess();

        $response = json_decode($this->response->getContent(), true);
        $responseData = $response['data'];

        $this->assertNotEmpty($responseData['id']);
        $this->assertModelData($actualData, $responseData);
    }

    public function assertResponseOk()
    {

    }


    public function assertApiSuccess()
    {
        $this->assertResponseOk();
        $this->assertJson(['success' => true]);
    }

    public function assertModelData(Array $actualData, Array $expectedData)
    {
        foreach ($actualData as $key => $value) {
            $this->assertEquals($actualData[$key], $expectedData[$key]);
        }
    }
}