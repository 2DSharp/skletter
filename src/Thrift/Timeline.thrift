namespace java com.skletter.Timeline.Remote
namespace php Skletter.Model.RemoteService.Timeline
include "PostOffice.thrift"
include "Romeo.thrift"

struct PostAggregate
{
    1: PostOffice.PostDTO post
    2: Romeo.PublicProfileDTO user
}
service Timeline
{
    void fanout(1: string postId, 2: i32 userId);
    list<PostAggregate> fetchTimeline(1: i32 userId);
    list<PostAggregate> fetchPartialTimelineTill(1: i32 userId, 2: string lastPostId)
}