namespace php Skletter.Model.RemoteService.PhotoBooth
enum ProfileVariant {
    SMALL,
    MEDIUM,
    BIG
}

enum ImageVariant {
    THUMBNAIL,
    ORIGINAL
}

enum ImageType {
    DISPLAY_PICTURE,
    ATTACHED
}
struct CropBox {
    1: required i16 x
    2: required i16 y
    3: required i16 side
}

struct ImageMeta {
    1: required ImageType type
    2: optional CropBox cropBox
}

service PhotoBooth
{
     string generateImageId();
     void uploadImage(1: string id, 2: string location, 3: ImageMeta meta);
     string getProfilePicture(1: string id, 2: ProfileVariant variant);
}