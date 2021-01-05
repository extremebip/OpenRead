<?php

namespace App\Models\Requests\Writer;

use App\Models\Requests\PostRequest;
use App\Service\Contracts\IWriterService;

class SaveStoryPostRequest extends PostRequest
{
    private $writerService;

    public function __construct(IWriterService $writerService) {
        $this->writerService = $writerService;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $data = $this->validationData();
        if (isset($data['story_id'])){
            $story = $this->writerService->GetStoryByID($data['story_id']);
            return (!is_null($story) && $this->user()->username == $story['username']);
        }
        return true;
    }

    private $maxCoverImageSize = 20000;

    private function getFileSizePostfix($size)
    {
        if ($size >= 1000000){
            return floor($size / 1000000).'GB';
        }
        else if ($size >= 1000){
            return floor($size / 1000).'MB';
        }
        else {
            return $size.'KB';
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'story_title' => ['required', 'string', 'min:5', 'max:50'],
            'cover' => ['sometimes', 'file', 'image', 'max:'.$this->maxCoverImageSize],
            'sinopsis' => ['required', 'string', 'max:65535'],
            'story_id' => ['sometimes', 'required', 'exists:stories,story_id'],
            'genres' => ['required', 'array', function ($attribute, $value, $fail)
            {
                $all_genre_ids = $this->writerService->GetGenres()->pluck('genre_id');
                foreach ($value as $item) {
                    if (!$all_genre_ids->contains($item))
                        $fail('Invalid genre input');
                }
            }],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'story_title.required' => 'Story Title must not be empty',
            'story_title.min' => 'Story Title must have at least :min characters',
            'story_title.max' => 'Story Title must not exceed :max characters',

            'cover.file' => 'Cover Image is not a valid image file',
            'cover.image' => 'Cover Image is not a valid image file',
            'cover.max' => 'Cover Image size must not exceed '.$this->getFileSizePostfix($this->maxCoverImageSize),

            'sinopsis.required' => 'Synopsis must not be empty',
            'sinopsis.max' => 'Synopsis must not exceed :max characters',

            'story_id.exists' => 'Story does not exist',

            'genres.required' => 'Select at least one genre',
            'genres.array' => 'Invalid genre input',
        ];
    }

    public function validated()
    {
        $validated = parent::validated();
        return array_merge($validated, [
            'username' => $this->user()->username,
            'genres' => collect($validated['genres'])->unique()
        ]);
    }
}
