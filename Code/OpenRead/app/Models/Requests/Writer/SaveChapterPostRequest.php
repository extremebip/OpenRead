<?php

namespace App\Models\Requests\Writer;

use App\Models\Requests\PostRequest;

class SaveChapterPostRequest extends PostRequest
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
        $story = $this->writerService->GetStoryByID($data['story_id']);
        return (!is_null($story) && $this->user()->username == $story->username);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'chapter_title' => ['required', 'string', 'min:5', 'max:50'],
            'content' => ['required', 'string', 'min:10', 'max:16777214'],
            'story_id' => ['required', 'exists:stories,story_id'],
            'chapter_id' => ['sometimes', 'required', 'exists:stories,story_id']
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
            'chapter_title.required' => 'Chapter Title must not be empty',
            'chapter_title.min' => 'Chapter Title must have at least :min characters',
            'chapter_title.max' => 'Chapter Title must not exceed :max characters',

            'content.min' => 'Content must have at least :min characters',
            'content.max' => 'Content must not exceed :max characters',

            'story_id.exists' => 'Story does not exist',

            'chapter_id.exists' => 'Chapter does not exist',
        ];
    }
}
