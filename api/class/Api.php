<?php

/**
 * Class Api
 */
class Api extends Controller
{
    /**
     *
     */
    public function admin_dashboard_get()
    {
        $this->_checkAuth();

        $model = $this->_getPostModel();

        ApiUtils::sendJsonResponse([
            'posts' => $model->getTotal(),
        ]);
    }

    /**
     *
     */
    public function admin_auth_post()
    {
        ApiUtils::sendJsonResponse(['result' => Auth::check()]);
    }

    /**
     * @param $id
     */
    public function admin_post_get($id)
    {
        $this->_checkAuth();

        $model = $this->_getPostModel();

        $data = $model->getBy(['id' => $id]);
        $data['created'] = Time::getTextTimestamp(strtotime($data['created']));

        ApiUtils::sendJsonResponse($data);
    }

    /**
     * @param $id
     */
    public function admin_video_get($id)
    {
        $this->_checkAuth();

        $model = $this->_getVideoModel();

        $data = $model->getBy(['id' => $id]);
        $data['created'] = Time::getTextTimestamp(strtotime($data['created']));

        ApiUtils::sendJsonResponse($data);
    }

    public function admin_post_post()
    {
        $this->_checkAuth();

        $item = Input::getRawBody(TRUE);

        if (!is_object($item)) {
            throw new InvalidArgumentException;
        }

        $model = $this->_getPostModel();

        $post = $model->insert($item);
        $post['created'] = Time::getTextTimestamp((int)$post['created']);

        $cache = new Cache();
        $cache->clean();

        ApiUtils::sendJsonResponse($post);
    }

    /**
     *
     */
    public function admin_video_post()
    {
        $this->_checkAuth();

        $item = Input::getRawBody(TRUE);

        if (!is_object($item)) {
            throw new InvalidArgumentException;
        }

        $model = $this->_getVideoModel();
        $post = $model->insert($item);
        $post['created'] = Time::getTextTimestamp((int)$post['created']);

        $cache = new Cache();
        $cache->clean();

        ApiUtils::sendJsonResponse($post);
    }

    /**
     * @param $id
     */
    public function admin_post_put($id)
    {
        $this->_checkAuth();

        $item = Input::getRawBody(TRUE);

        if (!is_object($item)) {
            throw new InvalidArgumentException;
        }

        $model = $this->_getPostModel();
        $model->update($id, $item);

        $cache = new Cache();
        $cache->clean();

        ApiUtils::sendJsonResponse($item);
    }

    /**
     * @param $id
     */
    public function admin_video_put($id)
    {
        $this->_checkAuth();

        $item = Input::getRawBody(TRUE);

        if (!is_object($item)) {
            throw new InvalidArgumentException;
        }

        $model = $this->_getVideoModel();
        $model->update($id, $item);

        $cache = new Cache();
        $cache->clean();

        ApiUtils::sendJsonResponse($item);
    }

    /**
     *
     */
    public function admin_posts_get()
    {
        $this->_checkAuth();

        $model = $this->_getPostModel();

        $data = $model->getAll();
        $data = $data ? $data : [];

        foreach ($data as &$post) {
            $post['created'] = Time::getTextTimestamp(strtotime($post['created']));
        }

        ApiUtils::sendJsonResponse($data);
    }

    /**
     *
     */
    public function admin_videos_get()
    {
        $this->_checkAuth();

        $model = $this->_getVideoModel();

        $data = $model->getAll();
        $data = $data ? $data : [];

        foreach ($data as &$post) {
            $post['created'] = Time::getTextTimestamp(strtotime($post['created']));
        }

        ApiUtils::sendJsonResponse($data);
    }

    /**
     *
     */
    public function login_post()
    {
        if (Auth::login(Input::post('username'), Input::post('password'))) {
            ApiUtils::sendJsonResponse(['success' => TRUE]);
        } else {
            ApiUtils::sendJsonResponse(
                [
                    'error' => [
                        'text' => 'You shall not pass...',
                    ],
                ]
            );
        }
    }

    /**
     * @return void
     */
    public function logout_post()
    {
        Auth::logout();
        ApiUtils::sendJsonResponse(['success' => TRUE]);
    }

    /**
     *
     */
    public function list_get()
    {
        $cache = new Cache();
        $cacheName = hash('md4', __METHOD__);

        if (!$data = $cache->get($cacheName)) {
            $model = $this->_getPostModel();
            $data = $model->getPublished();
            $cache->save($cacheName, $data);
        }

        if (!$data) {
            ApiUtils::sendJsonResponse([]);
        }

        foreach ($data as &$post) {
            $post['created'] = Time::getTextTimestamp(strtotime($post['created']));
        }

        ApiUtils::sendJsonResponse($data);
    }

    /**
     *
     */
    public function videos_get()
    {
        $cache = new Cache();
        $cacheName = hash('md4', __METHOD__);

        if (!$data = $cache->get($cacheName)) {
            $model = $this->_getVideoModel();
            $data = $model->getPublished();
            $cache->save($cacheName, $data);
        }

        if (!$data) {
            ApiUtils::sendJsonResponse([]);
        }

        foreach ($data as &$post) {
            $post['created'] = Time::getTextTimestamp(strtotime($post['created']));
            $post['list'] = explode("\n", $post['list']);
        }

        ApiUtils::sendJsonResponse($data);
    }

    /**
     * @param $id
     */
    public function post_get($id)
    {
        $cache = new Cache();
        $cacheName = hash('md4', __METHOD__ . '::' . $id);

        if (!$post = $cache->get($cacheName)) {
            $model = $this->_getPostModel();
            $post = $model->getBy(['id' => $id]);
            $cache->save($cacheName, $post);
        }

        if (!$post) {
            ApiUtils::sendJsonResponse([]);
        }

        $post['created'] = Time::getTextTimestamp(strtotime($post['created']));

        ApiUtils::sendJsonResponse($post);
    }

    /**
     *
     */
    public function recache_get()
    {
        $this->_checkAuth();

        $cache = new Cache();
        $cache->clean();
        ApiUtils::sendJsonResponse('Operation completed');
    }

    /**
     *
     */
    protected function _checkAuth()
    {
        if (!Auth::check()) {
            ApiUtils::sendPlainResponse(
                'Dude, you aren\'t logged in... sign in for me, will you?', TRUE, 401
            );
        }
    }

    /**
     * @return PostModel
     * @throws Exception
     */
    protected function _getPostModel()
    {
        /** @var PostModel $model */
        $model = DBConnection::init(new PostModel);
        $model->connect(DB_PATH);

        return $model;
    }

    /**
     * @return VideoModel
     * @throws Exception
     */
    protected function _getVideoModel()
    {
        /** @var VideoModel $model */
        $model = DBConnection::init(new VideoModel);
        $model->connect(DB_PATH);

        return $model;
    }
}