<?php
declare(strict_types=1);

namespace App\Service;

use Aws\S3\S3Client;
use Cake\Core\Configure;

/**
 * MinioService handles file uploads to MinIO (S3-compatible storage).
 */
class MinioService
{
    private const POLICY_VERSION = '2012-10-17';

    private S3Client $client;
    private string $bucket;

    /**
     * MinioService constructor.
     *
     * Reads MinIO configuration from CakePHP's Configure class,
     * initializes the S3 client, and sets the target bucket.
     *
     * @throws \Aws\Exception\AwsException If the client cannot be initialized
     */
    public function __construct()
    {
        $config = Configure::read('MinIO');
        $this->bucket = $config['bucket'];

        $this->client = new S3Client([
            'version' => 'latest',
            'region' => $config['region'],
            'endpoint' => $config['endpoint'],
            'credentials' => [
                'key' => $config['key'],
                'secret' => $config['secret'],
            ],
            'use_path_style_endpoint' => true,
        ]);
    }

    /**
     * Uploads a file to the configured MinIO bucket.
     *
     * Ensures the bucket exists before uploading. Returns the public URL
     * of the uploaded file.
     *
     * @param \Psr\Http\Message\UploadedFileInterface $file File object from CakePHP form
     * @param string $path Path within the bucket (e.g., "products/123-image.jpg")
     * @return string Public URL of the uploaded file
     * @throws \Aws\S3\Exception\S3Exception If the upload fails
     */
    public function upload($file, string $path): string
    {
        $this->ensureBucketExists();

        $this->client->putObject([
            'Bucket' => $this->bucket,
            'Key' => $path,
            'Body' => $file->getStream(),
            'ContentType' => $file->getClientMediaType(),
            'ACL' => 'public-read',
        ]);

        return 'http://localhost:9004/' . $this->bucket . '/' . $path;
    }

    /**
     * Ensures that the configured bucket exists in MinIO.
     *
     * If the bucket does not exist, it will be created automatically
     * along with a public-read policy to allow access to its objects.
     *
     * @return void
     * @throws \Aws\S3\Exception\S3Exception If bucket creation or policy application fails
     */
    private function ensureBucketExists(): void
    {
        try {
            $this->client->headBucket(['Bucket' => $this->bucket]);
        } catch (\Aws\S3\Exception\S3Exception $e) {
            if ($e->getStatusCode() === 404) {
                $this->client->createBucket(['Bucket' => $this->bucket]);
                $this->client->putBucketPolicy([
                    'Bucket' => $this->bucket,
                    'Policy' => json_encode([
                        'Version' => self::POLICY_VERSION,
                        'Statement' => [[
                            'Effect' => 'Allow',
                            'Principal' => '*',
                            'Action' => 's3:GetObject',
                            'Resource' => 'arn:aws:s3:::' . $this->bucket . '/*',
                        ]],
                    ]),
                ]);
            } else {
                throw $e;
            }
        }
    }
}
