<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Telegram\Bot\Laravel\Facades\Telegram;
use Tests\TestCase;

class FormControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the Telegram facade to avoid actual API calls
        Telegram::fake();
    }

    /**
     * Test that the homepage loads successfully.
     */
    public function test_homepage_returns_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Contact Us');
    }

    /**
     * Test web form submission with valid data.
     */
    public function test_web_form_submission_with_valid_data(): void
    {
        $formData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'This is a test message for the contact form.',
        ];

        $response = $this->post('/submit', $formData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Form submitted successfully!');

        // Assert Telegram message was sent
        Telegram::assertSent(function ($message) {
            return $message->chat_id === env('TELEGRAM_CHAT_ID')
                && str_contains($message->text, 'John Doe')
                && str_contains($message->text, 'john@example.com')
                && str_contains($message->text, 'This is a test message for the contact form.');
        });
    }

    /**
     * Test web form submission with invalid data.
     */
    public function test_web_form_submission_with_invalid_data(): void
    {
        $response = $this->post('/submit', [
            'name' => '',
            'email' => 'invalid-email',
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'message']);
        $response->assertRedirect();

        // Assert no Telegram message was sent
        Telegram::assertNotSent();
    }

    /**
     * Test web form submission with missing fields.
     */
    public function test_web_form_submission_with_missing_fields(): void
    {
        $response = $this->post('/submit', [
            'name' => 'John Doe',
            // missing email and message
        ]);

        $response->assertSessionHasErrors(['email', 'message']);
    }

    /**
     * Test API form submission with valid data.
     */
    public function test_api_form_submission_with_valid_data(): void
    {
        $formData = [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'message' => 'API test message content here.',
        ];

        $response = $this->postJson('/api/submit', $formData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Form submitted successfully']);

        // Assert Telegram message was sent
        Telegram::assertSent(function ($message) {
            return $message->chat_id === env('TELEGRAM_CHAT_ID')
                && str_contains($message->text, 'Jane Smith')
                && str_contains($message->text, 'jane@example.com')
                && str_contains($message->text, 'API test message content here.');
        });
    }

    /**
     * Test API form submission with invalid data.
     */
    public function test_api_form_submission_with_invalid_data(): void
    {
        $response = $this->postJson('/api/submit', [
            'name' => '',
            'email' => 'not-an-email',
            'message' => str_repeat('a', 5001), // exceeds max length
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'message']);

        // Assert no Telegram message was sent
        Telegram::assertNotSent();
    }

    /**
     * Test demo submission endpoint with valid data.
     */
    public function test_demo_submission_with_valid_data(): void
    {
        $demoData = [
            'username' => 'testuser',
            'password' => 'testpassword123',
        ];

        $response = $this->postJson('/api/demo-submit', $demoData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Demo submission forwarded to Telegram',
                'data' => $demoData,
            ]);

        // Assert Telegram message was sent with demo format
        Telegram::assertSent(function ($message) {
            return $message->chat_id === env('TELEGRAM_CHAT_ID')
                && str_contains($message->text, 'SECURITY AWARENESS DEMO')
                && str_contains($message->text, 'testuser')
                && str_contains($message->text, 'testpassword123');
        });
    }

    /**
     * Test demo submission endpoint with invalid data.
     */
    public function test_demo_submission_with_invalid_data(): void
    {
        $response = $this->postJson('/api/demo-submit', [
            'username' => '',
            'password' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['username', 'password']);

        // Assert no Telegram message was sent
        Telegram::assertNotSent();
    }

    /**
     * Test that form submission sends correct Telegram message format.
     */
    public function test_form_submission_sends_correct_telegram_format(): void
    {
        $formData = [
            'name' => 'Alice Wonderland',
            'email' => 'alice@wonderland.com',
            'message' => 'Hello from the other side!',
        ];

        $this->post('/submit', $formData);

        Telegram::assertSent(function ($message) {
            $expectedText = "📬 New Form Submission\n"
                . "━━━━━━━━━━━━━━━━━━\n"
                . "👤 Name: Alice Wonderland\n"
                . "📧 Email: alice@wonderland.com\n"
                . "💬 Message:\nHello from the other side!";

            return $message->chat_id === env('TELEGRAM_CHAT_ID')
                && $message->text === $expectedText
                && $message->parse_mode === 'HTML';
        });
    }

    /**
     * Test that demo submission sends correct Telegram message format.
     */
    public function test_demo_submission_sends_correct_telegram_format(): void
    {
        $demoData = [
            'username' => 'demo_user',
            'password' => 'demo_pass',
        ];

        $this->postJson('/api/demo-submit', $demoData);

        Telegram::assertSent(function ($message) {
            $expectedText = "🎓 SECURITY AWARENESS DEMO\n"
                . "━━━━━━━━━━━━━━━━━━\n"
                . "This is a FAKE sign-in page used for training.\n\n"
                . "👤 Username/Email: demo_user\n"
                . "🔑 Password: demo_pass";

            return $message->chat_id === env('TELEGRAM_CHAT_ID')
                && $message->text === $expectedText
                && $message->parse_mode === 'HTML';
        });
    }
}
