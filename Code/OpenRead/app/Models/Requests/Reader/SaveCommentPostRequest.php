<?php

namespace App\Models\Requests\Reader;

use Illuminate\Support\Facades\Auth;
use App\Models\Requests\Base\APIRequest;
use App\Service\Contracts\IReaderService;

class SaveCommentPostRequest extends APIRequest
{
    private $readerService;

    public function __construct(IReaderService $readerService) {
        $this->readerService = $readerService;
    }

    protected function setErrorResult()
    {
        $data = $this->validationData();
        if (isset($data['comment_id']))
            return ['for' => 'edit'];
        else
            return ['for' => 'create'];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $data = $this->validationData();
        if (!Auth::check())
            return false;
        else if (isset($data['comment_id'])){
            $comment = $this->readerService->GetCommentByID($data['comment_id']);
            return (!is_null($comment) && $this->user()->username == $comment->username);
        }
        else
            return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'chapter_id' => ['required', 'exists:chapters,chapter_id'],
            'content' => ['required', 'string', 'min:10', 'max:65535'],
            'comment_id' => ['sometimes', 'required', 'exists:comments,comment_id']
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
            'content.required' => 'Content must not be empty',
            'content.min' => 'Content must have at least :min characters',
            'content.max' => 'Content must not exceed :max characters',

            'chapter_id.required' => 'Invalid comment input',
            'chapter_id.exists' => 'Chapter does not exist',

            'comment_id.required' => 'Invalid comment input',
            'comment_id.exists' => 'Comment does not exist'
        ];
    }

    public function validated()
    {
        return array_merge(parent::validated(), [
            'username' => $this->user()->username
        ]);
    }
}
