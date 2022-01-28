<?php

namespace Tests\Feature\Backend\Facebook;

use App\Models\FacebookFlow;

class FlowControllerTest extends \Tests\TestCase
{

    public function testSaveFacebookBotFlowXMLFlow()
    {
        $request = [
            'flow' => "<main>
            <bot>
                <message>
                    <attachment>
                        <type>template</type>
                        <payload>
                            <template_type>button</template_type>
                            <text>Hi {{Firstname}}! Welcome. Please choose from the options below:</text>
                            <buttons>
                                <type>web_url</type>
                                <url>http://example.com</url>
                                <title>Visit messenger</title>
                            </buttons>
                        </payload>
                    </attachment>
                </message>
                <goto>displayProducts</goto>
            </bot>
            <displayProducts>
                <bot>
                    <message>
                        <text>Do you want to continue?</text>
                        <quick_replies>
                            <content_type>text</content_type>
                            <title>Yes</title>
                            <payload>
                                <action>yes_continue</action>
                            </payload>
                        </quick_replies>
                    </message>
                </bot>
            </displayProducts>
            <yes_continue>
                
            </yes_continue>
        </main>",
        ];
        $response = $this->json('POST', route('admin.facebook.flow.post.create'), $request);

        $response->assertStatus(200);

    }

    public function testUpdateFacebookFlow()
    {
        $flow = new FacebookFlow();
        $result = $flow->insertOne([
            'flow' => "<main>
            <bot>
                <message>
                    <attachment>
                        <type>template</type>
                        <payload>
                            <template_type>button</template_type>
                            <text>Hi {{Firstname}}! Welcome. Please choose from the options below:</text>
                            <buttons>
                                <type>web_url</type>
                                <url>http://example.com</url>
                                <title>Visit messenger</title>
                            </buttons>
                        </payload>
                    </attachment>
                </message>
                <goto>displayProducts</goto>
            </bot>
            <displayProducts>
                <bot>
                    <message>
                        <text>Do you want to continue?</text>
                        <quick_replies>
                            <content_type>text</content_type>
                            <title>Yes</title>
                            <payload>
                                <action>yes_continue</action>
                            </payload>
                        </quick_replies>
                    </message>
                </bot>
            </displayProducts>
            <yes_continue>
                
            </yes_continue>
        </main>"
        ]);

        $id = $result->getInsertedId()->__toString();
        $request = [
            'id' => $id,
            'flow' => "<displayProducts>
            <bot>
                <message>
                    <text>Do you want to continue?</text>
                    <quick_replies>
                        <content_type>text</content_type>
                        <title>Yes</title>
                        <payload>
                            <action>yes_continue</action>
                        </payload>
                    </quick_replies>
                </message>
            </bot>
        </displayProducts>"
        ];

        $response = $this->json('POST', route('admin.facebook.flow.post.create'), $request);

        $response->assertStatus(200);

    }



}