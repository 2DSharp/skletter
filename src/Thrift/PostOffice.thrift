namespace java com.skletter.PostOffice.Remote
namespace php Skletter.Model.RemoteService.PostOffice

struct PostDTO {
    1: required string id
    2: required string title
    3: required string content
    4: optional string time
    5: required i32 userId
}
service PostOffice
{
    void registerPublicPost(1: PostDTO post);
    PostDTO getPublicPost(1: string id);
    list<PostDTO> fetchPublicPosts(1: list<string> postIds)
    list<PostDTO> fetchPostsFromUser(1: i32 userId, 2: i16 limit)
    list<PostDTO> fetchPostFromUsers(1: list<i32> userIds, 2: i16 limit)
}